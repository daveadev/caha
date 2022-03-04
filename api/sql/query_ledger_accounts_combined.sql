SELECT
	*,  # Select all fields
	LRT.run_total = APT.outstanding_balance as is_balance_matched, # Compare ledger run total vs account OB
	APT.total_paid =  APT.payment_total as is_pay_matched  # Compare payment total vs paysched payments
FROM
	(
	# Ledger Run Total (LRT)
	SELECT
		a.id as account_id,
		GROUP_CONCAT(l.transaction_type_id) as trnx,  # Related Transactions
		GROUP_CONCAT( CONCAT(l.`type` , l.amount) ) as amounts, 
		sum( if(l.`type` = '-', l.amount*-1, l.amount) ) as run_total # Compute SUM + -
	from
		ledgers l
	inner join accounts a on
		(l.account_id = a.id)
	where
		l.esp = 2021  # All SY 2021 only
	group by
		l.account_id) as LRT
inner join (
	# Account Payments Total
	SELECT
		a.id as account_id,
		a.assessment_total , 
		a.discount_amount , # Stored discount amount negative
		a.payment_total , # Stored precomputed payment
		a.old_balance , # Stored Old Account
		a.outstanding_balance , # Stored precomputed OB  =  AT - DA - PT + OA
		sum(as2.due_amount) as total_due , # Paysched Dues
		sum(as2.paid_amount) as total_paid # Paysched Payments
	from
		accounts a
	inner join account_schedules as2 on
		(a.id = as2.account_id) # Join to paysched
	group by
		as2.account_id ) as APT on
	(LRT.account_id = APT.account_id)