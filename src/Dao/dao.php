<?php

namespace Dao;

use Misc\Db;
use PDO;
use PDOException;

class Dao
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
        $this->query1();
    }

    private function query1(): void
    {
        $conn = $this->db->getConnection();
        $sql = file_get_contents("src/Queries/q3.sql");

        if (!$sql) {
            echo "Could not read SQL file src/Queries/q3.sql";
            die();
        }

        $query = $conn->prepare($sql);

        //$query->bindValue(":", "");

        try {
            if (!$query->execute()) {
                throw new PDOException("Could not execute the SQL statement");
            }

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $uuids = explode("__", $row["production_date"]);
                $day = $uuids[0];
                $month = $uuids[1];
                $year = $uuids[2];
                //echo "$day/$month/$year<br/>";

                $sql2 = "UPDATE Clothes SET year_uuid = :year, month_uuid = :month, day_uuid = :day WHERE cloth_uuid = :uuid";
                $query2 = $conn->prepare($sql2);
                $query2->bindParam(":year", $year);
                $query2->bindParam(":month", $month);
                $query2->bindParam(":day", $day);
                $query2->bindParam(":uuid", $row['cloth_uuid']);
                $query2->execute();
            }

        } catch (PDOException $e) {
            echo "Could not execute the SQL statement to register the client" . $e->getMessage();
        }

    }
}