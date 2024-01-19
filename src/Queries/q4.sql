# Se obtienen todos los pantalones fabricados
# en la fecha, hora y turno indicados

SELECT COUNT(*)
FROM Distribution_log dLog
    INNER JOIN Clothes c ON dLog.cloth_uuid = c.cloth_uuid
    INNER JOIN Types t ON c.type = t.type_uuid
    INNER JOIN Days d ON c.day_uuid = d.day_uuid
    INNER JOIN Months m ON c.month_uuid = m.month_uuid
    INNER JOIN Years y ON c.year_uuid = y.year_uuid
    INNER JOIN Turns turn ON c.turn = turn.turn_uuid
    INNER JOIN Hours startH ON turn.start_hour = startH.hour_uuid
    INNER JOIN Hours endH ON turn.end_hour = endH.hour_uuid
WHERE
    (t.type = "LEGGINS" OR t.type = "JEANS" OR t.type = "SHORTS")
AND
    (d.day = 26 AND m.month = 9 AND y.year = 2023)
AND
    (
        (startH.hour = 8 AND end_hour = 16)
        OR
        (startH.hour = 16 AND endH.hour = 0)
    )
AND
    (dLog.destination_store is not null)

