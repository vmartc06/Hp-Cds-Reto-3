SELECT COUNT(*)
FROM Distribution_log dLog
WHERE
    (dLog.destination_store is not null);