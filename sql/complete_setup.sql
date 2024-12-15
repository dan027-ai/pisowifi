-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `piso_wifi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `piso_wifi`;

-- Create vouchers table
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `description` text,
  `quantity_limit` INT NULL,
  `remaining_quantity` INT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `is_promo` TINYINT(1) DEFAULT 0,
  `promo_end_time` DATETIME NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create transactions table
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11),
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(20) NOT NULL DEFAULT 'gcash',
  `status` varchar(255) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create admin_users table
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create total_sales table
CREATE TABLE IF NOT EXISTS `total_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transactions_count` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create trigger for updating total_sales
DELIMITER //
CREATE TRIGGER IF NOT EXISTS update_total_sales 
AFTER INSERT ON transactions
FOR EACH ROW
BEGIN
    INSERT INTO total_sales (date, total_amount, transactions_count)
    VALUES (DATE(NEW.created_at), NEW.amount, 1)
    ON DUPLICATE KEY UPDATE
    total_amount = total_amount + NEW.amount,
    transactions_count = transactions_count + 1;
END;//
DELIMITER ;

-- Insert sample voucher data
INSERT INTO `vouchers` (`price`, `duration`, `description`, `is_active`) VALUES
(20.00, '1 hour', '1 Hour Internet Access', 1),
(50.00, '3 hours', '3 Hours Internet Access', 1),
(100.00, '8 hours', '8 Hours Internet Access', 1),
(150.00, '24 hours', '24 Hours Internet Access', 1);

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO `admin_users` (`username`, `password`) VALUES
('admin', '$2y$10$your_hashed_password_here');