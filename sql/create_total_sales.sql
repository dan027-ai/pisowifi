CREATE TABLE IF NOT EXISTS `total_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transactions_count` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trigger to update total_sales after each transaction
DELIMITER //
CREATE TRIGGER update_total_sales AFTER INSERT ON transactions
FOR EACH ROW
BEGIN
    INSERT INTO total_sales (date, total_amount, transactions_count)
    VALUES (DATE(NEW.created_at), NEW.amount, 1)
    ON DUPLICATE KEY UPDATE
    total_amount = total_amount + NEW.amount,
    transactions_count = transactions_count + 1;
END;//
DELIMITER ;