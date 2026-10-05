SELECT 
c.expense_category_name,
SUM(e.amount) AS expenses_in_category
FROM
expense_category AS c
INNER JOIN
expenses AS e
ON c.expense_category_id = e.expense_category_id
WHERE 
e.expense_date BETWEEN '2026-08-01' AND '2026-09-01' 
AND e.user_id = c.user_id
GROUP BY
c.expense_category_id, c.expense_category_name
ORDER BY 
expenses_in_category DESC




