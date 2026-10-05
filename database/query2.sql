--Kopiowanie domyślnych kategorii przychodów dla nowego użytkownika--

INSERT INTO incomes_category_assigned_to_users (user_id, name)
SELECT :userId, name
FROM incomes_category_default;

--Pobranie bilansu przychodów w wybranym przedziale czasowym--

SELECT 
c.name,
SUM(i.amount) AS amount_in_category
FROM
incomes_category_assigned_to_users AS c
INNER JOIN
incomes AS i
ON c.id = i.income_category_assigned_to_user_id
WHERE 
i.date_of_income BETWEEN :startDate AND :endDate 
AND i.user_id = :userId
GROUP BY
c.id, c.name
ORDER BY 
amount_in_category DESC;


