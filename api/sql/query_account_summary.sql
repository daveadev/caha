SELECT
	a.id,
	a.assessment_total ,
	a.discount_amount ,
	a.payment_total ,
	a.old_balance ,
	a.outstanding_balance ,
	sum(as2.due_amount) as total_due ,
	sum(as2.paid_amount) as total_paid
from
	accounts a
inner join account_schedules as2 on
	(a.id = as2.account_id)
group by
	as2.account_id