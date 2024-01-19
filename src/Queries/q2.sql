UPDATE clothes
SET
    type = REPLACE(REPLACE(type, "'", ''), '"', '');