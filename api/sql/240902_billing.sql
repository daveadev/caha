SELECT s1.id, s1.sno, s1.last_name , s1.first_name,s1.mobile,sec.name,latest_billing.bill_id, bill1.due_amount  FROM students s1 inner join (SELECT
	stud.id as acct_id,
	MAX(bill.id) as bill_id,
	bill.due_amount as due_amount
FROm
	students as stud
inner join 
	caha_pay_240901.billings as bill on
	(bill.account_id = stud.id)
	
group by
	bill.account_id
	) as latest_billing
	on (s1.id = latest_billing.acct_id)
	inner join caha_pay_240901.billings as bill1 on (latest_billing.bill_id= bill1.id)
	inner join sections sec ON (sec.id =  s1.section_id) order by sec.id, s1.last_name, s1.first_name