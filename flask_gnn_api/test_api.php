<?php
/**
 * Test GNN API Connection
 * File untuk mengecek koneksi ke Flask GNN API
 * 
 * Cara Pakai:
 * 1. Pastikan Flask API sudah running
 * 2. Buka browser: http://localhost/adaptivetes/flask_gnn_api/test_api.php
 * 3. Atau jalankan: php test_api.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GNN API Connection Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .container {
            max-width: 900px;
        }
        .test-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        .status-warning {
            background: #fff3cd;
            color: #856404;
        }
        .code-block {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
        }
        .test-result {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
        }
        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .metric-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
        }
        .metric-value {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-card">
            <h1 class="text-center mb-4">
                <i class="fas fa-plug"></i> GNN API Connection Test
            </h1>
            <p class="text-center text-muted mb-4">
                Mengecek koneksi ke Flask GNN API di berbagai port
            </p>

            <?php
            // Fungsi untuk test API
            function testAPIConnection($port) {
                $url = "http://localhost:{$port}/health";
                $ch = curl_init($url);
                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                
                $start_time = microtime(true);
                $response = curl_exec($ch);
                $end_time = microtime(true);
                
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_error = curl_error($ch);
                $response_time = round(($end_time - $start_time) * 1000, 2); // ms
                
                curl_close($ch);
                
                return [
                    'success' => ($http_code == 200),
                    'http_code' => $http_code,
                    'response' => $response,
                    'response_time' => $response_time,
                    'error' => $curl_error,
                    'url' => $url
                ];
            }

            // Fungsi untuk test predict endpoint
            function testPredictEndpoint($port) {
                $url = "http://localhost:{$port}/predict";
                $data = [
                    'student_id' => 1,
                    'module_results' => [
                        [
                            'module_id' => 1,
                            'correct_answers' => 2,
                            'total_questions' => 3
                        ]
                    ]
                ];
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
                
                $start_time = microtime(true);
                $response = curl_exec($ch);
                $end_time = microtime(true);
                
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_error = curl_error($ch);
                $response_time = round(($end_time - $start_time) * 1000, 2);
                
                curl_close($ch);
                
                return [
                    'success' => ($http_code == 200),
                    'http_code' => $http_code,
                    'response' => $response,
                    'response_time' => $response_time,
                    'error' => $curl_error,
                    'url' => $url
                ];
            }

            // Test berbagai port
            $ports = [5001, 5000, 5002];
            $working_port = null;
            $test_results = [];

            echo '<div class="alert alert-info">';
            echo '<i class="fas fa-info-circle me-2"></i>';
            echo 'Mencoba koneksi ke Flask API...';
            echo '</div>';

            foreach ($ports as $port) {
                $result = testAPIConnection($port);
                $test_results[$port] = $result;
                
                if ($result['success'] && !$working_port) {
                    $working_port = $port;
                }
            }
            ?>

            <!-- Health Check Results -->
            <h4 class="mb-3"><i class="fas fa-heartbeat me-2"></i>Health Check Results</h4>
            <?php foreach ($test_results as $port => $result): ?>
                <div class="test-result border <?php echo $result['success'] ? 'border-success' : 'border-danger'; ?>">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">
                            Port <?php echo $port; ?>
                            <?php if ($port === 5001): ?>
                                <span class="badge bg-primary">Default</span>
                            <?php endif; ?>
                        </h5>
                        <span class="status-badge <?php echo $result['success'] ? 'status-success' : 'status-error'; ?>">
                            <?php if ($result['success']): ?>
                                <i class="fas fa-check-circle"></i> CONNECTED
                            <?php else: ?>
                                <i class="fas fa-times-circle"></i> FAILED
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <small class="text-muted">URL:</small><br>
                            <code><?php echo $result['url']; ?></code>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">HTTP Code:</small><br>
                            <strong><?php echo $result['http_code'] ?: 'N/A'; ?></strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Response Time:</small><br>
                            <strong><?php echo $result['response_time']; ?> ms</strong>
                        </div>
                    </div>
                    
                    <?php if ($result['success']): ?>
                        <div class="mt-3">
                            <small class="text-muted">Response:</small>
                            <div class="code-block">
                                <?php 
                                $json = json_decode($result['response'], true);
                                echo '<pre>' . json_encode($json, JSON_PRETTY_PRINT) . '</pre>';
                                ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="mt-3">
                            <small class="text-muted">Error:</small>
                            <div class="alert alert-danger mb-0">
                                <?php echo $result['error'] ?: 'Connection failed'; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- Predict Endpoint Test -->
            <?php if ($working_port): ?>
                <hr class="my-4">
                <h4 class="mb-3"><i class="fas fa-brain me-2"></i>Predict Endpoint Test</h4>
                <?php
                $predict_result = testPredictEndpoint($working_port);
                ?>
                <div class="test-result border <?php echo $predict_result['success'] ? 'border-success' : 'border-warning'; ?>">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">POST /predict</h5>
                        <span class="status-badge <?php echo $predict_result['success'] ? 'status-success' : 'status-warning'; ?>">
                            <?php if ($predict_result['success']): ?>
                                <i class="fas fa-check-circle"></i> SUCCESS
                            <?php else: ?>
                                <i class="fas fa-exclamation-circle"></i> ERROR
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <small class="text-muted">URL:</small><br>
                            <code><?php echo $predict_result['url']; ?></code>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">HTTP Code:</small><br>
                            <strong><?php echo $predict_result['http_code']; ?></strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Response Time:</small><br>
                            <strong><?php echo $predict_result['response_time']; ?> ms</strong>
                        </div>
                    </div>
                    
                    <?php if ($predict_result['success']): ?>
                        <div class="mt-3">
                            <small class="text-muted">Response:</small>
                            <div class="code-block">
                                <?php 
                                $json = json_decode($predict_result['response'], true);
                                echo '<pre>' . json_encode($json, JSON_PRETTY_PRINT) . '</pre>';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Summary -->
            <hr class="my-4">
            <h4 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Summary</h4>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="metric-card">
                        <div class="metric-value">
                            <?php echo $working_port ? "Port {$working_port}" : "N/A"; ?>
                        </div>
                        <div>Active Port</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="metric-card">
                        <div class="metric-value">
                            <?php 
                            $success_count = count(array_filter($test_results, function($r) { return $r['success']; }));
                            echo "{$success_count}/" . count($test_results);
                            ?>
                        </div>
                        <div>Ports Available</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="metric-card">
                        <div class="metric-value">
                            <?php 
                            if ($working_port && isset($test_results[$working_port])) {
                                echo $test_results[$working_port]['response_time'] . "ms";
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </div>
                        <div>Response Time</div>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="alert alert-info">
                <h5><i class="fas fa-lightbulb me-2"></i>Rekomendasi:</h5>
                <?php if ($working_port): ?>
                    <p class="mb-2">✅ Flask API sudah running dan terhubung di <strong>port <?php echo $working_port; ?></strong></p>
                    <p class="mb-2">✅ Sistem PHP akan otomatis menggunakan port ini</p>
                    <p class="mb-0">✅ Anda dapat mulai menggunakan fitur Adaptive Learning</p>
                <?php else: ?>
                    <p class="mb-2">❌ Flask API tidak terdeteksi di semua port yang dicoba</p>
                    <p class="mb-2">📝 Pastikan Flask API sudah running dengan command:</p>
                    <div class="code-block">
                        python c:\laragon\www\adaptivetes\flask_gnn_api\app.py
                    </div>
                    <p class="mb-0 mt-2">📖 Lihat panduan lengkap di: <code>RUN_FLASK_MANUAL.md</code></p>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <button onclick="location.reload()" class="btn btn-primary btn-lg">
                    <i class="fas fa-sync-alt me-2"></i>Test Ulang
                </button>
                <?php if ($working_port): ?>
                    <a href="http://localhost:<?php echo $working_port; ?>/health" target="_blank" class="btn btn-success btn-lg">
                        <i class="fas fa-external-link-alt me-2"></i>Buka API Health
                    </a>
                <?php endif; ?>
                <a href="../student/index.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-home me-2"></i>Ke Dashboard
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-white">
            <small>
                <i class="fas fa-clock me-1"></i>
                Last checked: <?php echo date('Y-m-d H:i:s'); ?>
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
