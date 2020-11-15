ALTER TABLE `transactions` ADD `amount` DECIMAL(10,2) NOT NULL AFTER `ref_no`;
ALTER TABLE `transactions` ADD `esp` DECIMAL(6,2) NOT NULL AFTER `amount`;
ALTER TABLE `transactions` ADD `cashier` VARCHAR(15) NOT NULL AFTER `account_id`;