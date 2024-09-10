ALTER TABLE billings ADD `statement` text NULL;
ALTER TABLE billings CHANGE `statement` `statement` text NULL AFTER due_amount;
ALTER TABLE billings ADD sy int NULL;
ALTER TABLE billings CHANGE sy sy int NULL AFTER `statement`;
UPDATE billings  set sy = 2024;
