-- Table untuk menyimpan jawaban pretest per modul dengan GNN
CREATE TABLE `pretest_modul` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `module_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer_id` int NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_pretest_modul_student_id` (`student_id`),
  KEY `fk_pretest_modul_module_id` (`module_id`),
  KEY `idx_student_module` (`student_id`, `module_id`),
  CONSTRAINT `fk_pretest_modul_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pretest_modul_module_id` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table untuk menyimpan hasil perhitungan GNN
CREATE TABLE `result_hasil_pretest` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `module_id` int NOT NULL,
  `total_questions` int NOT NULL DEFAULT 3,
  `correct_answers` int NOT NULL DEFAULT 0,
  `score` int NOT NULL DEFAULT 0,
  `gnn_prediction` float DEFAULT NULL,
  `gnn_confidence` float DEFAULT NULL,
  `recommended_level` int NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_module` (`student_id`, `module_id`),
  KEY `fk_result_pretest_student_id` (`student_id`),
  KEY `fk_result_pretest_module_id` (`module_id`),
  CONSTRAINT `fk_result_pretest_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_result_pretest_module_id` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index untuk performa query
CREATE INDEX idx_student_score ON result_hasil_pretest(student_id, score);
CREATE INDEX idx_module_level ON result_hasil_pretest(module_id, recommended_level);
CREATE INDEX idx_created_at ON pretest_modul(created_at);
