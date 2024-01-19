SELECT c.UUID AS UUID, originStore.NAME AS ORIGIN, destinationStore.NAME AS DESTINATION, dLog.TRANSACTION_DATE
FROM Distribution_log dLog
         INNER JOIN Clothes c ON dLog.CLOTH = c.UUID
         INNER JOIN Cloth_Types cT ON c.TYPE = cT.UUID
         INNER JOIN Stores originStore ON dLog.ORIGIN_STORE = originStore.UUID
         INNER JOIN Stores destinationStore ON dLog.DESTINATION_STORE = destinationStore.UUID
         INNER JOIN Turns t ON c.PRODUCED_TURN = t.UUID
WHERE
    (cT.NAME = "LEGGINS" OR cT.NAME = "JEANS" OR cT.NAME = "SHORTS")
  AND
    (c.PRODUCED_ON = '2023-09-26')
  AND
    (
        (t.START = 8 AND t.END = 16)
            OR
        (t.START = 16 AND t.END = 0)
    )
  AND
    (dLog.DESTINATION_STORE is not null);