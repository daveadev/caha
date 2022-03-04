SELECT
	a.id,
	GROUP_CONCAT(l.transaction_type_id) as trnx,
	GROUP_CONCAT(
	CONCAT(l.`type` ,l.amount)
	) as amounts,
	sum(
	if(l.`type`='-',l.amount*-1,l.amount)
	) as run_total
from
	ledgers l
inner join accounts a on
	(l.account_id = a.id)
where
	l.esp = 2021
group by
	l.account_id