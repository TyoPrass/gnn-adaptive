-- Tabel untuk menyimpan hasil post-test adaptive learning
CREATE TABLE IF NOT EXISTS `post_test_adaptive_result` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `module_id` int NOT NULL,
  `correct_answers` int NOT NULL DEFAULT 0,
  `total_questions` int NOT NULL DEFAULT 0,
  `score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` enum('lulus','gagal') NOT NULL DEFAULT 'gagal',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_post_test_adaptive_student` (`student_id`),
  KEY `fk_post_test_adaptive_module` (`module_id`),
  KEY `idx_student_module` (`student_id`, `module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tambahkan foreign key constraints
ALTER TABLE `post_test_adaptive_result`
  ADD CONSTRAINT `fk_post_test_adaptive_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_test_adaptive_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
