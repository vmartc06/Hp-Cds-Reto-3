SELECT t.turn_uuid AS TURN, startH.hour AS START, endH.hour AS END
FROM Turns t
         INNER JOIN Hours startH ON t.start_hour = startH.hour_uuid
         INNER JOIN Hours endH ON t.end_hour = endH.hour_uuid;