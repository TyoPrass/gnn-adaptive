# Test Flask GNN API menggunakan PowerShell
# Cara run: .\test_api.ps1

$API_URL = "http://localhost:5000"

# Colors for output
function Write-Success { param($message) Write-Host "✓ $message" -ForegroundColor Green }
function Write-Error-Custom { param($message) Write-Host "✗ $message" -ForegroundColor Red }
function Write-Info { param($message) Write-Host "ℹ $message" -ForegroundColor Cyan }
function Write-Warning-Custom { param($message) Write-Host "⚠ $message" -ForegroundColor Yellow }

function Write-Header {
    param($title)
    Write-Host ""
    Write-Host ("="*60) -ForegroundColor Cyan
    Write-Host $title.PadLeft(($title.Length + 60)/2).PadRight(60) -ForegroundColor Cyan
    Write-Host ("="*60) -ForegroundColor Cyan
    Write-Host ""
}

# Test 1: Health Check
function Test-HealthCheck {
    Write-Header "Test 1: Health Check"
    
    try {
        Write-Info "Testing GET $API_URL/health"
        $response = Invoke-RestMethod -Uri "$API_URL/health" -Method Get -TimeoutSec 10
        
        Write-Host "`nResponse:" -ForegroundColor Yellow
        $response | ConvertTo-Json -Depth 10
        
        if ($response.status -eq "ok") {
            Write-Success "Health check passed!"
            return $true
        }
        else {
            Write-Error-Custom "Health check failed - Invalid response"
            return $false
        }
    }
    catch {
        Write-Error-Custom "Health check failed: $_"
        return $false
    }
}

# Test 2: Prediction
function Test-Prediction {
    Write-Header "Test 2: Prediction Endpoint"
    
    $body = @{
        student_id = 431
        module_results = @(
            @{ module_id = 1; correct_answers = 3; total_questions = 3 },
            @{ module_id = 2; correct_answers = 2; total_questions = 3 },
            @{ module_id = 3; correct_answers = 1; total_questions = 3 },
            @{ module_id = 4; correct_answers = 3; total_questions = 3 },
            @{ module_id = 5; correct_answers = 2; total_questions = 3 },
            @{ module_id = 6; correct_answers = 1; total_questions = 3 },
            @{ module_id = 7; correct_answers = 2; total_questions = 3 }
        )
    }
    
    try {
        Write-Info "Testing POST $API_URL/predict"
        Write-Host "`nRequest:" -ForegroundColor Yellow
        $body | ConvertTo-Json -Depth 10
        
        $jsonBody = $body | ConvertTo-Json -Depth 10
        $response = Invoke-RestMethod -Uri "$API_URL/predict" -Method Post -Body $jsonBody -ContentType "application/json" -TimeoutSec 30
        
        Write-Host "`nResponse:" -ForegroundColor Yellow
        $response | ConvertTo-Json -Depth 10
        
        if ($response.predictions -and $response.overall_level) {
            Write-Host "`nAnalysis:" -ForegroundColor Yellow
            Write-Success "Overall Level: $($response.overall_level)"
            Write-Success "Average Score: $($response.average_score)"
            Write-Success "Recommended Module: $($response.recommended_start_module)"
            
            Write-Host "`nModule Predictions:" -ForegroundColor Yellow
            foreach ($pred in $response.predictions) {
                $color = if ($pred.score -ge 85) { "Green" } elseif ($pred.score -ge 50) { "Yellow" } else { "Red" }
                Write-Host "  Module $($pred.module_id): Score=$($pred.score), Level=$($pred.predicted_level), Confidence=$($pred.confidence.ToString('F2'))" -ForegroundColor $color
            }
            
            Write-Success "Prediction test passed!"
            return $true
        }
        else {
            Write-Error-Custom "Prediction failed - Invalid response structure"
            return $false
        }
    }
    catch {
        Write-Error-Custom "Prediction failed: $_"
        return $false
    }
}

# Test 3: Score Calculation
function Test-ScoreCalculation {
    Write-Header "Test 3: Score Calculation"
    
    $testCases = @(
        @{ correct_answers = 0; total_questions = 3; expected = 0; desc = "No correct answers" },
        @{ correct_answers = 1; total_questions = 3; expected = 50; desc = "One correct answer" },
        @{ correct_answers = 2; total_questions = 3; expected = 85; desc = "Two correct answers" },
        @{ correct_answers = 3; total_questions = 3; expected = 100; desc = "All correct" }
    )
    
    $passed = 0
    $failed = 0
    
    foreach ($test in $testCases) {
        Write-Host "`nTest: $($test.desc)" -ForegroundColor Yellow
        Write-Host "Input: $($test.correct_answers)/$($test.total_questions) correct"
        
        $body = @{
            correct_answers = $test.correct_answers
            total_questions = $test.total_questions
        } | ConvertTo-Json
        
        try {
            $response = Invoke-RestMethod -Uri "$API_URL/calculate_score" -Method Post -Body $body -ContentType "application/json" -TimeoutSec 10
            
            if ($response.score -eq $test.expected) {
                Write-Success "Score = $($response.score) (expected $($test.expected))"
                $passed++
            }
            else {
                Write-Error-Custom "Score = $($response.score) (expected $($test.expected))"
                $failed++
            }
        }
        catch {
            Write-Error-Custom "Test failed: $_"
            $failed++
        }
    }
    
    Write-Host "`nSummary:" -ForegroundColor Yellow
    Write-Host "  Passed: $passed/$($testCases.Count)" -ForegroundColor Green
    Write-Host "  Failed: $failed/$($testCases.Count)" -ForegroundColor Red
    
    return ($failed -eq 0)
}

# Check API availability
function Test-APIAvailability {
    Write-Info "Checking if API is available..."
    
    try {
        $response = Invoke-RestMethod -Uri "$API_URL/health" -Method Get -TimeoutSec 5
        if ($response.status -eq "ok") {
            Write-Success "API is running and accessible!"
            return $true
        }
    }
    catch {
        Write-Error-Custom "Cannot connect to API at $API_URL"
        Write-Warning-Custom "Please start Flask API first: python app.py"
        return $false
    }
}

# Main execution
function Main {
    Write-Host ""
    Write-Host ("="*60) -ForegroundColor Magenta
    Write-Host "  Flask GNN API - PowerShell Test Suite" -ForegroundColor Magenta
    Write-Host "  $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Magenta
    Write-Host ("="*60) -ForegroundColor Magenta
    
    # Check API availability
    if (-not (Test-APIAvailability)) {
        Write-Host ""
        return 1
    }
    
    # Run tests
    $results = @{
        "Health Check" = Test-HealthCheck
        "Prediction" = Test-Prediction
        "Score Calculation" = Test-ScoreCalculation
    }
    
    # Summary
    Write-Header "Test Results Summary"
    
    $passed = 0
    $failed = 0
    
    foreach ($test in $results.GetEnumerator()) {
        if ($test.Value) {
            Write-Success "$($test.Key): PASSED"
            $passed++
        }
        else {
            Write-Error-Custom "$($test.Key): FAILED"
            $failed++
        }
    }
    
    Write-Host "`nOverall:" -ForegroundColor Yellow
    Write-Host "  Total:  $($results.Count) tests"
    Write-Host "  Passed: $passed" -ForegroundColor Green
    Write-Host "  Failed: $failed" -ForegroundColor Red
    
    Write-Host ""
    Write-Host ("="*60) -ForegroundColor $(if ($failed -eq 0) { "Green" } else { "Red" })
    
    if ($failed -eq 0) {
        Write-Host "  ✓✓✓ ALL TESTS PASSED! ✓✓✓".PadLeft(35) -ForegroundColor Green
    }
    else {
        Write-Host "  ✗✗✗ SOME TESTS FAILED! ✗✗✗".PadLeft(37) -ForegroundColor Red
    }
    
    Write-Host ("="*60) -ForegroundColor $(if ($failed -eq 0) { "Green" } else { "Red" })
    Write-Host ""
    
    return $failed
}

# Run main
exit (Main)
