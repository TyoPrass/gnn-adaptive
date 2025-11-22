"""
Test Script untuk GNN Adaptive Learning Flask API

Cara menjalankan:
    python test_api.py

Atau test spesifik endpoint:
    python test_api.py health
    python test_api.py predict
    python test_api.py score
"""

import requests
import json
import sys
import time
from datetime import datetime

# API Base URL
API_URL = "http://localhost:5000"

# ANSI color codes untuk output berwarna
class Colors:
    GREEN = '\033[92m'
    RED = '\033[91m'
    YELLOW = '\033[93m'
    BLUE = '\033[94m'
    MAGENTA = '\033[95m'
    CYAN = '\033[96m'
    BOLD = '\033[1m'
    END = '\033[0m'

def print_header(text):
    """Print header dengan garis"""
    print(f"\n{Colors.BOLD}{Colors.CYAN}{'='*60}{Colors.END}")
    print(f"{Colors.BOLD}{Colors.CYAN}{text.center(60)}{Colors.END}")
    print(f"{Colors.BOLD}{Colors.CYAN}{'='*60}{Colors.END}\n")

def print_success(text):
    """Print success message"""
    print(f"{Colors.GREEN}✓ {text}{Colors.END}")

def print_error(text):
    """Print error message"""
    print(f"{Colors.RED}✗ {text}{Colors.END}")

def print_info(text):
    """Print info message"""
    print(f"{Colors.BLUE}ℹ {text}{Colors.END}")

def print_warning(text):
    """Print warning message"""
    print(f"{Colors.YELLOW}⚠ {text}{Colors.END}")

def check_api_availability():
    """Check if API is running"""
    print_info("Checking if API is available...")
    try:
        response = requests.get(f"{API_URL}/health", timeout=5)
        if response.status_code == 200:
            print_success("API is running and accessible!")
            return True
        else:
            print_error(f"API returned status code: {response.status_code}")
            return False
    except requests.exceptions.ConnectionError:
        print_error("Cannot connect to API. Is Flask running?")
        print_warning("Please run: python app.py")
        return False
    except Exception as e:
        print_error(f"Error checking API: {str(e)}")
        return False


def test_health_check():
    """Test health check endpoint"""
    print_header("Test 1: Health Check Endpoint")
    
    print_info("Testing GET /health")
    print(f"URL: {API_URL}/health\n")
    
    try:
        start_time = time.time()
        response = requests.get(f"{API_URL}/health", timeout=10)
        elapsed_time = time.time() - start_time
        
        print_info(f"Response time: {elapsed_time:.3f} seconds")
        print_info(f"Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            print(f"\n{Colors.BOLD}Response:{Colors.END}")
            print(json.dumps(data, indent=2))
            
            # Validate response structure
            if 'status' in data and data['status'] == 'ok':
                print_success("Health check passed!")
                return True
            else:
                print_error("Health check response invalid")
                return False
        else:
            print_error(f"Health check failed with status {response.status_code}")
            return False
            
    except Exception as e:
        print_error(f"Health check error: {str(e)}")
        return False


def test_predict():
    """Test prediction endpoint"""
    print_header("Test 2: Prediction Endpoint")
    
    # Sample data - 7 modules with varying performance
    data = {
        "student_id": 431,
        "module_results": [
            {"module_id": 1, "correct_answers": 3, "total_questions": 3},
            {"module_id": 2, "correct_answers": 2, "total_questions": 3},
            {"module_id": 3, "correct_answers": 1, "total_questions": 3},
            {"module_id": 4, "correct_answers": 3, "total_questions": 3},
            {"module_id": 5, "correct_answers": 2, "total_questions": 3},
            {"module_id": 6, "correct_answers": 1, "total_questions": 3},
            {"module_id": 7, "correct_answers": 2, "total_questions": 3}
        ]
    }
    
    print_info("Testing POST /predict")
    print(f"URL: {API_URL}/predict\n")
    
    print(f"{Colors.BOLD}Request Data:{Colors.END}")
    print(json.dumps(data, indent=2))
    print()
    
    try:
        start_time = time.time()
        response = requests.post(
            f"{API_URL}/predict",
            json=data,
            headers={"Content-Type": "application/json"},
            timeout=30
        )
        elapsed_time = time.time() - start_time
        
        print_info(f"Response time: {elapsed_time:.3f} seconds")
        print_info(f"Status Code: {response.status_code}")
        
        if response.status_code == 200:
            result = response.json()
            print(f"\n{Colors.BOLD}Response:{Colors.END}")
            print(json.dumps(result, indent=2))
            
            # Validate response structure
            if 'predictions' in result and 'overall_level' in result:
                print(f"\n{Colors.BOLD}Analysis:{Colors.END}")
                print_success(f"Overall Level: {result['overall_level']}")
                print_success(f"Average Score: {result['average_score']:.1f}")
                print_success(f"Recommended Start Module: {result['recommended_start_module']}")
                
                print(f"\n{Colors.BOLD}Module Predictions:{Colors.END}")
                for pred in result['predictions']:
                    module_id = pred['module_id']
                    score = pred['score']
                    level = pred['predicted_level']
                    confidence = pred['confidence']
                    
                    # Color code by score
                    if score >= 85:
                        color = Colors.GREEN
                    elif score >= 50:
                        color = Colors.YELLOW
                    else:
                        color = Colors.RED
                    
                    print(f"  Module {module_id}: {color}Score={score}, Level={level}, Confidence={confidence:.2f}{Colors.END}")
                
                print_success("Prediction test passed!")
                return True
            else:
                print_error("Response structure invalid")
                return False
        else:
            print_error(f"Prediction failed with status {response.status_code}")
            if response.text:
                print(f"Response: {response.text}")
            return False
            
    except Exception as e:
        print_error(f"Prediction error: {str(e)}")
        return False


def test_calculate_score():
    """Test score calculation endpoint"""
    print_header("Test 3: Score Calculation Endpoint")
    
    test_cases = [
        {"correct_answers": 0, "total_questions": 3, "expected": 0, "desc": "No correct answers"},
        {"correct_answers": 1, "total_questions": 3, "expected": 50, "desc": "One correct answer"},
        {"correct_answers": 2, "total_questions": 3, "expected": 85, "desc": "Two correct answers"},
        {"correct_answers": 3, "total_questions": 3, "expected": 100, "desc": "All correct"}
    ]
    
    print_info("Testing POST /calculate_score")
    print(f"URL: {API_URL}/calculate_score\n")
    
    all_passed = True
    passed_count = 0
    failed_count = 0
    
    for i, test_case in enumerate(test_cases, 1):
        data = {
            "correct_answers": test_case["correct_answers"],
            "total_questions": test_case["total_questions"]
        }
        
        print(f"{Colors.BOLD}Test Case {i}: {test_case['desc']}{Colors.END}")
        print(f"Input: {test_case['correct_answers']}/{test_case['total_questions']} correct")
        
        try:
            start_time = time.time()
            response = requests.post(
                f"{API_URL}/calculate_score",
                json=data,
                headers={"Content-Type": "application/json"},
                timeout=10
            )
            elapsed_time = time.time() - start_time
            
            if response.status_code == 200:
                result = response.json()
                score = result.get('score', -1)
                expected = test_case['expected']
                
                if score == expected:
                    print_success(f"Score = {score} (expected {expected}) ✓ [{elapsed_time:.3f}s]")
                    passed_count += 1
                else:
                    print_error(f"Score = {score} (expected {expected}) ✗")
                    all_passed = False
                    failed_count += 1
            else:
                print_error(f"Request failed with status {response.status_code}")
                all_passed = False
                failed_count += 1
                
        except Exception as e:
            print_error(f"Error: {str(e)}")
            all_passed = False
            failed_count += 1
        
        print()
    
    # Summary
    print(f"{Colors.BOLD}Summary:{Colors.END}")
    print(f"  Passed: {Colors.GREEN}{passed_count}/{len(test_cases)}{Colors.END}")
    print(f"  Failed: {Colors.RED}{failed_count}/{len(test_cases)}{Colors.END}")
    
    if all_passed:
        print_success("All score calculation tests passed!")
    else:
        print_error("Some tests failed!")
    
    return all_passed


def run_all_tests():
    """Run all API tests"""
    print(f"\n{Colors.BOLD}{Colors.MAGENTA}{'#'*60}{Colors.END}")
    print(f"{Colors.BOLD}{Colors.MAGENTA}# GNN Adaptive Learning Flask API - Test Suite{Colors.END}")
    print(f"{Colors.BOLD}{Colors.MAGENTA}# {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}{Colors.END}")
    print(f"{Colors.BOLD}{Colors.MAGENTA}{'#'*60}{Colors.END}")
    
    # Check if API is available first
    if not check_api_availability():
        print_error("\nCannot proceed with tests. Please start Flask API first.")
        print_info("Run: python app.py")
        return 1
    
    print()
    
    # Run tests
    results = {}
    
    print_info("Running all tests...\n")
    
    results["Health Check"] = test_health_check()
    results["Prediction"] = test_predict()
    results["Score Calculation"] = test_calculate_score()
    
    # Summary
    print_header("Test Results Summary")
    
    total_tests = len(results)
    passed_tests = sum(1 for v in results.values() if v)
    failed_tests = total_tests - passed_tests
    
    for test_name, passed in results.items():
        status = f"{Colors.GREEN}✓ PASSED{Colors.END}" if passed else f"{Colors.RED}✗ FAILED{Colors.END}"
        print(f"  {test_name}: {status}")
    
    print(f"\n{Colors.BOLD}Overall:{Colors.END}")
    print(f"  Total:  {total_tests} tests")
    print(f"  Passed: {Colors.GREEN}{passed_tests}{Colors.END}")
    print(f"  Failed: {Colors.RED}{failed_tests}{Colors.END}")
    
    print(f"\n{'='*60}\n")
    
    if all(results.values()):
        print(f"{Colors.BOLD}{Colors.GREEN}{'='*60}")
        print(f"  ✓✓✓ ALL TESTS PASSED! ✓✓✓".center(60))
        print(f"{'='*60}{Colors.END}\n")
        return 0
    else:
        print(f"{Colors.BOLD}{Colors.RED}{'='*60}")
        print(f"  ✗✗✗ SOME TESTS FAILED! ✗✗✗".center(60))
        print(f"{'='*60}{Colors.END}\n")
        return 1

def run_specific_test(test_name):
    """Run a specific test"""
    print(f"\n{Colors.BOLD}{Colors.MAGENTA}{'#'*60}{Colors.END}")
    print(f"{Colors.BOLD}{Colors.MAGENTA}# Running Specific Test: {test_name}{Colors.END}")
    print(f"{Colors.BOLD}{Colors.MAGENTA}{'#'*60}{Colors.END}")
    
    if not check_api_availability():
        print_error("\nCannot proceed with test. Please start Flask API first.")
        return 1
    
    test_map = {
        'health': test_health_check,
        'predict': test_predict,
        'prediction': test_predict,
        'score': test_calculate_score,
        'calculate': test_calculate_score
    }
    
    test_func = test_map.get(test_name.lower())
    
    if test_func:
        result = test_func()
        if result:
            print_success(f"\n{test_name.upper()} test passed!")
            return 0
        else:
            print_error(f"\n{test_name.upper()} test failed!")
            return 1
    else:
        print_error(f"Unknown test: {test_name}")
        print_info("Available tests: health, predict, score")
        return 1

def show_usage():
    """Show usage information"""
    print(f"""
{Colors.BOLD}Usage:{Colors.END}
    python test_api.py              # Run all tests
    python test_api.py <test_name>  # Run specific test
    
{Colors.BOLD}Available Tests:{Colors.END}
    health      - Test health check endpoint
    predict     - Test prediction endpoint  
    score       - Test score calculation endpoint

{Colors.BOLD}Examples:{Colors.END}
    python test_api.py
    python test_api.py health
    python test_api.py predict
    python test_api.py score

{Colors.BOLD}Requirements:{Colors.END}
    - Flask API must be running (python app.py)
    - API should be accessible at http://localhost:5000
""")

if __name__ == "__main__":
    try:
        if len(sys.argv) > 1:
            if sys.argv[1] in ['-h', '--help', 'help']:
                show_usage()
                sys.exit(0)
            else:
                sys.exit(run_specific_test(sys.argv[1]))
        else:
            sys.exit(run_all_tests())
    except KeyboardInterrupt:
        print(f"\n\n{Colors.YELLOW}Tests interrupted by user{Colors.END}")
        sys.exit(1)
    except Exception as e:
        print_error(f"\nUnexpected error: {str(e)}")
        sys.exit(1)
