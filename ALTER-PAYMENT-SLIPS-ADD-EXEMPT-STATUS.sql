-- Alter payment_slips table to add 'exempt' status for City Events
-- This allows City Government events to be tracked without payment requirement

ALTER TABLE payment_slips 
MODIFY COLUMN status ENUM('paid', 'unpaid', 'expired', 'exempt') NOT NULL;

-- Verify the change
SHOW COLUMNS FROM payment_slips LIKE 'status';

