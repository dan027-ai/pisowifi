ALTER TABLE transactions
ADD COLUMN payment_method VARCHAR(20) NOT NULL DEFAULT 'gcash';