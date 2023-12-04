-- Define variables
SET @ref_date = '2023-11-15';
SET @school_year = 2023;

-- Main query
SELECT
    a.id,
    ask.count_overdue,
    ask.total_overdue,
    l.last_paid_ref_no,
    l.last_paid_date,
    @ref_date AS reference_date,
    ask.total_due - total_paid AS total_net_unpaid,
    ask.due_amounts,
    l.payments_made
FROM
    accounts a
INNER JOIN (
    -- Subquery for overdue amounts and related calculations
    SELECT
        *,
        GROUP_CONCAT(bill_month,'-',due_amount) as dues, -- Concatenating bill_month and due_amount
        GROUP_CONCAT(bill_month,'-',CASE WHEN due_date <= @ref_date AND status != 'PAID' THEN due_amount-paid_amount ELSE 0 END) as due_amounts, -- Explanation of due_amounts
        SUM(CASE WHEN due_date <= @ref_date AND status != 'PAID' THEN due_amount-paid_amount ELSE 0 END) AS total_overdue, -- Total overdue amount
        SUM(CASE WHEN due_date <= @ref_date AND status != 'PAID' THEN 1 ELSE 0 END) AS count_overdue, -- Count of overdue entries
        SUM(due_amount) as total_due -- Total due amount
    FROM
        account_schedules
    GROUP BY
        account_id
) AS ask ON a.id = ask.account_id
INNER JOIN (
    -- Subquery for ledger information and related calculations
    SELECT
        *,
        GROUP_CONCAT(ref_no,'-',amount, '-',transaction_type_id) as payments_made, -- Concatenating ref_no, amount, and transaction_type_id
        Max(transac_date) as last_paid_date, -- Maximum transaction date
        MAX(ref_no) as last_paid_ref_no, -- Maximum ref_no
        SUM(CASE WHEN transaction_type_id != 'OLDAC' THEN amount ELSE 0 END) AS total_paid -- Total amount paid
    FROM
        ledgers
    WHERE
        esp = @school_year -- Filter by school year
        AND ref_no LIKE 'OR%'
    GROUP BY
        account_id
) AS l ON a.id = l.account_id
ORDER BY
    ask.total_overdue ASC; -- Order by total_overdue in ascending order
