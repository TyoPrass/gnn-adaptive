<?php
/**
 * GNN Helper Functions
 * File ini berisi fungsi-fungsi helper untuk integrasi dengan GNN API
 */

/**
 * Get student pretest results dengan GNN predictions
 * 
 * @param mysqli $conn Database connection
 * @param int $student_id Student ID
 * @return array Student results with GNN predictions
 */
function getStudentPretestResults($conn, $student_id) {
    $sql = "SELECT 
                rhp.*,
                m.module_desc,
                m.module_level,
                m.number as module_number
            FROM result_hasil_pretest rhp
            JOIN module m ON m.id = rhp.module_id
            WHERE rhp.student_id = '{$student_id}'
            ORDER BY m.number ASC";
    
    $query = mysqli_query($conn, $sql);
    $results = array();
    
    while ($row = mysqli_fetch_assoc($query)) {
        $results[] = $row;
    }
    
    return $results;
}

/**
 * Get detailed answers for a student's pretest
 * 
 * @param mysqli $conn Database connection
 * @param int $student_id Student ID
 * @param int $module_id Module ID (optional)
 * @return array Detailed answers
 */
function getStudentPretestDetails($conn, $student_id, $module_id = null) {
    $sql = "SELECT 
                pm.*,
                mq.question,
                mqc.answer_desc,
                m.module_desc
            FROM pretest_modul pm
            JOIN module_question mq ON mq.id = pm.question_id
            JOIN module_question_choice mqc ON mqc.id = pm.answer_id
            JOIN module m ON m.id = pm.module_id
            WHERE pm.student_id = '{$student_id}'";
    
    if ($module_id !== null) {
        $sql .= " AND pm.module_id = '{$module_id}'";
    }
    
    $sql .= " ORDER BY pm.module_id ASC, pm.question_id ASC";
    
    $query = mysqli_query($conn, $sql);
    $results = array();
    
    while ($row = mysqli_fetch_assoc($query)) {
        $results[] = $row;
    }
    
    return $results;
}

/**
 * Calculate module score based on correct answers
 * 
 * @param int $correct_answers Number of correct answers
 * @param int $total_questions Total number of questions
 * @return int Score (0, 50, 85, or 100)
 */
function calculateModuleScore($correct_answers, $total_questions = 3) {
    if ($correct_answers == 0) {
        return 0;
    } else if ($correct_answers == 1) {
        return 50;
    } else if ($correct_answers == 2) {
        return 85;
    } else if ($correct_answers >= 3) {
        return 100;
    }
    return 0;
}

/**
 * Get recommended learning level based on GNN prediction
 * 
 * @param float $gnn_prediction GNN prediction value
 * @param float $confidence Confidence level
 * @return int Recommended level (1, 2, or 3)
 */
function getRecommendedLevel($gnn_prediction, $confidence) {
    // If confidence is high, use GNN prediction directly
    if ($confidence >= 0.7) {
        return round($gnn_prediction);
    }
    
    // Otherwise, use conservative approach
    if ($gnn_prediction >= 2.5) {
        return 3;
    } else if ($gnn_prediction >= 1.5) {
        return 2;
    } else {
        return 1;
    }
}

/**
 * Get student's overall performance summary
 * 
 * @param mysqli $conn Database connection
 * @param int $student_id Student ID
 * @return array Performance summary
 */
function getStudentPerformanceSummary($conn, $student_id) {
    $sql = "SELECT 
                COUNT(*) as total_modules,
                AVG(score) as average_score,
                SUM(correct_answers) as total_correct,
                SUM(total_questions) as total_questions,
                AVG(gnn_confidence) as average_confidence,
                AVG(recommended_level) as average_level
            FROM result_hasil_pretest
            WHERE student_id = '{$student_id}'";
    
    $query = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($query);
}

/**
 * Get next recommended module for student
 * 
 * @param mysqli $conn Database connection
 * @param int $student_id Student ID
 * @return array Next recommended module info
 */
function getNextRecommendedModule($conn, $student_id) {
    // Get modules not yet learned
    $sql = "SELECT m.*, rhp.score, rhp.recommended_level
            FROM module m
            LEFT JOIN module_learned ml ON ml.module_id = m.id AND ml.student_id = '{$student_id}'
            LEFT JOIN result_hasil_pretest rhp ON rhp.module_id = m.id AND rhp.student_id = '{$student_id}'
            WHERE ml.id IS NULL
            ORDER BY 
                CASE 
                    WHEN rhp.score IS NULL THEN 0
                    WHEN rhp.score < 50 THEN 1
                    WHEN rhp.score < 85 THEN 2
                    ELSE 3
                END,
                m.number ASC
            LIMIT 1";
    
    $query = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($query);
}

/**
 * Check if student has completed pretest
 * 
 * @param mysqli $conn Database connection
 * @param int $student_id Student ID
 * @return bool True if completed, false otherwise
 */
function hasPretestCompleted($conn, $student_id) {
    $sql = "SELECT COUNT(*) as count 
            FROM result_hasil_pretest 
            WHERE student_id = '{$student_id}'";
    
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    
    return $result['count'] > 0;
}

/**
 * Get student's weak modules (score < 85)
 * 
 * @param mysqli $conn Database connection
 * @param int $student_id Student ID
 * @return array List of weak modules
 */
function getWeakModules($conn, $student_id) {
    $sql = "SELECT 
                rhp.*,
                m.module_desc,
                m.module_level
            FROM result_hasil_pretest rhp
            JOIN module m ON m.id = rhp.module_id
            WHERE rhp.student_id = '{$student_id}'
            AND rhp.score < 85
            ORDER BY rhp.score ASC, m.number ASC";
    
    $query = mysqli_query($conn, $sql);
    $results = array();
    
    while ($row = mysqli_fetch_assoc($query)) {
        $results[] = $row;
    }
    
    return $results;
}

/**
 * Get student's strong modules (score >= 85)
 * 
 * @param mysqli $conn Database connection
 * @param int $student_id Student ID
 * @return array List of strong modules
 */
function getStrongModules($conn, $student_id) {
    $sql = "SELECT 
                rhp.*,
                m.module_desc,
                m.module_level
            FROM result_hasil_pretest rhp
            JOIN module m ON m.id = rhp.module_id
            WHERE rhp.student_id = '{$student_id}'
            AND rhp.score >= 85
            ORDER BY rhp.score DESC, m.number ASC";
    
    $query = mysqli_query($conn, $sql);
    $results = array();
    
    while ($row = mysqli_fetch_assoc($query)) {
        $results[] = $row;
    }
    
    return $results;
}

/**
 * Get learning path recommendation based on GNN
 * 
 * @param mysqli $conn Database connection
 * @param int $student_id Student ID
 * @return array Ordered list of modules to learn
 */
function getLearningPath($conn, $student_id) {
    $sql = "SELECT 
                m.*,
                rhp.score,
                rhp.recommended_level,
                rhp.gnn_confidence,
                CASE 
                    WHEN ml.id IS NOT NULL THEN 1
                    ELSE 0
                END as is_learned
            FROM module m
            LEFT JOIN result_hasil_pretest rhp ON rhp.module_id = m.id AND rhp.student_id = '{$student_id}'
            LEFT JOIN module_learned ml ON ml.module_id = m.id AND ml.student_id = '{$student_id}'
            ORDER BY 
                is_learned ASC,
                CASE 
                    WHEN rhp.score IS NULL THEN 1
                    WHEN rhp.score < 50 THEN 2
                    WHEN rhp.score < 85 THEN 3
                    ELSE 4
                END,
                m.number ASC";
    
    $query = mysqli_query($conn, $sql);
    $results = array();
    
    while ($row = mysqli_fetch_assoc($query)) {
        $results[] = $row;
    }
    
    return $results;
}

/**
 * Format score to display with color coding
 * 
 * @param int $score Score value
 * @return string HTML formatted score
 */
function formatScore($score) {
    if ($score >= 85) {
        $color = 'success';
        $icon = 'check-circle';
    } else if ($score >= 50) {
        $color = 'warning';
        $icon = 'exclamation-circle';
    } else {
        $color = 'danger';
        $icon = 'times-circle';
    }
    
    return "<span class='badge bg-{$color}'><i class='fas fa-{$icon}'></i> {$score}</span>";
}

/**
 * Get level label
 * 
 * @param int $level Level number (1, 2, or 3)
 * @return string Level label
 */
function getLevelLabel($level) {
    switch ($level) {
        case 1:
            return '<span class="badge bg-info">Dasar</span>';
        case 2:
            return '<span class="badge bg-primary">Menengah</span>';
        case 3:
            return '<span class="badge bg-success">Lanjut</span>';
        default:
            return '<span class="badge bg-secondary">Unknown</span>';
    }
}

/**
 * Export student results to JSON for analysis
 * 
 * @param mysqli $conn Database connection
 * @param int $student_id Student ID
 * @return string JSON string
 */
function exportStudentResults($conn, $student_id) {
    $results = getStudentPretestResults($conn, $student_id);
    $details = getStudentPretestDetails($conn, $student_id);
    $summary = getStudentPerformanceSummary($conn, $student_id);
    
    $export = array(
        'student_id' => $student_id,
        'summary' => $summary,
        'module_results' => $results,
        'detailed_answers' => $details,
        'timestamp' => date('Y-m-d H:i:s')
    );
    
    return json_encode($export, JSON_PRETTY_PRINT);
}
