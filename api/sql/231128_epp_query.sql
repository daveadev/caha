-- Define variables
SET @ref_date = '2023-11-15';
SET @school_year = 2023;

-- Main query
SELECT
    a.id,
    pps.count_overdue,
    pps.total_overdue,
    ppl.last_paid_ref_no,
    ppl.last_paid_date,
    @ref_date AS reference_date,
    pps.total_due - total_paid AS total_net_unpaid,
    pps.due_amounts,
    ppl.payments_made
FROM
    accounts a
INNER JOIN (
    -- Subquery for overdue amounts and related calculations
    SELECT
        pp.account_id,
        GROUP_CONCAT(DATE_FORMAT(due_date,'%b %Y'),'-',due_amount) as dues, -- Concatenating bill_month and due_amount
        GROUP_CONCAT(DATE_FORMAT(due_date,'%b %Y'),'-',CASE WHEN due_date <= @ref_date AND status != 'PAID' THEN due_amount-paid_amount ELSE 0 END) as due_amounts, -- Explanation of due_amounts
        SUM(CASE WHEN due_date <= @ref_date AND status != 'PAID' THEN due_amount-paid_amount ELSE 0 END) AS total_overdue, -- Total overdue amount
        SUM(CASE WHEN due_date <= @ref_date AND status != 'PAID' THEN 1 ELSE 0 END) AS count_overdue, -- Count of overdue entries
        SUM(due_amount) as total_due -- Total due amount
    FROM
        pay_plan_schedules ps
       inner join payment_plans pp on (pp.id =  ps.payment_plan_id)
     GROUP BY
        pp.account_id
) AS pps ON a.id = pps.account_id
LEFT JOIN (
    -- Subquery for ledger information and related calculations
    SELECT
        *,
        GROUP_CONCAT(ref_no,'-',amount, '-',transaction_type_id) as payments_made, -- Concatenating ref_no, amount, and transaction_type_id
        Max(transac_date) as last_paid_date, -- Maximum transaction date
        MAX(ref_no) as last_paid_ref_no, -- Maximum ref_no
        SUM(CASE WHEN transaction_type_id != 'OLDAC' THEN amount ELSE 0 END) AS total_paid -- Total amount paid
    FROM
        payplan_ledgers pl
    WHERE
        pl.esp = @school_year -- Filter by school year
        AND pl.ref_no LIKE 'OR%'
    GROUP BY
        account_id
) AS ppl ON a.id =ppl.account_id
ORDER BY
    pps.total_overdue ASC; -- Order by total_overdue in ascending order
