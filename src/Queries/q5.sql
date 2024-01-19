UPDATE Turns
SET
    start_hour = REPLACE(REPLACE(start_hour, 'H-', ''), '"', ''),
    end_hour = REPLACE(REPLACE(end_hour, 'H-', ''), '"', '');