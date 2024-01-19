SELECT *
FROM Types t
    INNER JOIN Clothes c ON c.type = t.type_uuid
WHERE
    (t.type = "LEGGINS" OR t.type = "JEANS" OR t.type = "SHORTS")
