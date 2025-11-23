-- Update table result_hasil_pretest: tambah kolom method dan gnn_predicted_level
ALTER TABLE `result_hasil_pretest` 
ADD COLUMN `method` VARCHAR(50) DEFAULT 'IRT' COMMENT 'Method used: GNN, IRT, or Hybrid' AFTER `gnn_confidence`,
ADD COLUMN `gnn_predicted_level` INT DEFAULT NULL COMMENT 'Predicted level from GNN model' AFTER `gnn_confidence`;

-- Add index for better query performance
CREATE INDEX idx_method ON result_hasil_pretest(method);
CREATE INDEX idx_gnn_predicted_level ON result_hasil_pretest(gnn_predicted_level);

-- Optional: Add total_score column untuk aggregate score
ALTER TABLE `result_hasil_pretest`
ADD COLUMN `percentage` DECIMAL(5,2) DEFAULT 0.00  AFTER `score`;

-- Update existing records if any
UPDATE `result_hasil_pretest` 
SET `percentage` = (correct_answers / total_questions) * 100 
WHERE `percentage` = 0;
