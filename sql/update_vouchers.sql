ALTER TABLE vouchers 
ADD COLUMN IF NOT EXISTS quantity_limit INT NULL,
ADD COLUMN IF NOT EXISTS remaining_quantity INT NULL,
ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1,
ADD COLUMN IF NOT EXISTS is_promo TINYINT(1) DEFAULT 0,
ADD COLUMN IF NOT EXISTS promo_end_time DATETIME NULL;

-- Update existing vouchers to be active
UPDATE vouchers SET is_active = 1 WHERE is_active IS NULL;

-- Set remaining_quantity equal to quantity_limit for existing vouchers
UPDATE vouchers 
SET remaining_quantity = quantity_limit 
WHERE quantity_limit IS NOT NULL AND remaining_quantity IS NULL;