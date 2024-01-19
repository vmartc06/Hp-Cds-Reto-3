<?php

namespace Misc;

use PDO;
use PDOException;

class Converter
{
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function convert(): void
    {
        $this->transferTypes();
        $this->transferTurns();
        $this->transferProducers();
        $this->transferClothes();
        $this->transferStores();
        $this->transferDistributionLog();
    }

    private function transferTypes(): void
    {
        $connOld = $this->db->getConnection();
        $connNew = $this->db->getConnectionNew();

        $sql = "SELECT * FROM Types";

        $query = $connOld->prepare($sql);

        try {
            if (!$query->execute()) {
                throw new PDOException("Could not execute the SQL statement");
            }

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $sql2 = "INSERT INTO Cloth_Types (UUID, NAME) VALUES (:uuid, :name)";
                $query2 = $connNew->prepare($sql2);
                $query2->bindParam(":uuid", $row['type_uuid']);
                $query2->bindParam(":name", $row['type']);
                $query2->execute();
            }

        } catch (PDOException $e) {
            echo "Could not execute the SQL statement to transfer the Types table. Exception message: " . $e->getMessage();
        }

    }

    private function transferTurns(): void
    {
        $connOld = $this->db->getConnection();
        $connNew = $this->db->getConnectionNew();

        $sql = file_get_contents("src/Queries/turns1.sql");
        $query = $connOld->prepare($sql);

        try {
            if (!$query->execute()) {
                throw new PDOException("Could not execute the SQL statement");
            }

            $sql2 = file_get_contents("src/Queries/turns2.sql");
            $query2 = $connOld->prepare($sql2);

            if (!$query2->execute()) {
                throw new PDOException("Could not execute the SQL statement");
            }

            $rows = $query2->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $sql3 = "INSERT INTO Turns (UUID, START, END) VALUES (:uuid, :start, :end)";
                $query3 = $connNew->prepare($sql3);
                $query3->bindParam(":uuid", $row['TURN']);
                $query3->bindParam(":start", $row['START']);
                $query3->bindParam(":end", $row['END']);
                $query3->execute();
            }

        } catch (PDOException $e) {
            echo "Could not execute the SQL statement to transfer the Turns table. Exception message: " . $e->getMessage();
        }
    }

    private function transferProducers(): void
    {
        $connOld = $this->db->getConnection();
        $connNew = $this->db->getConnectionNew();

        $sql = "SELECT * FROM Producers";
        $query = $connOld->prepare($sql);

        try {
            if (!$query->execute()) {
                throw new PDOException("Could not execute the SQL statement");
            }

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $rawMetadata = $row['metadata'];

                $metadataObject = json_decode($rawMetadata, true);

                $name = $metadataObject['name'];
                $rating = $metadataObject['rating'];
                $category = $metadataObject['category'];
                $location = $metadataObject['location'];

                $sql2 = "INSERT INTO Producers (UUID, NAME, RATING, CATEGORY, LOCATION) VALUES (:uuid, :name, :rating, :category, :location)";
                $query2 = $connNew->prepare($sql2);
                $query2->bindParam(":uuid", $row['producer_uuid']);
                $query2->bindParam(":name", $name);
                $query2->bindParam(":rating", $rating);
                $query2->bindParam(":category", $category);
                $query2->bindParam(":location", $location);
                $query2->execute();
            }

        } catch (PDOException $e) {
            echo "Could not execute the SQL statement to transfer the Producers table. Exception message: " . $e->getMessage();
        }
    }

    private function transferClothes(): void
    {
        $connOld = $this->db->getConnection();
        $connNew = $this->db->getConnectionNew();

        $sql = "SELECT * FROM Clothes";
        $query = $connOld->prepare($sql);

        try {
            if (!$query->execute()) {
                throw new PDOException("Could not execute the SQL statement");
            }

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $type = str_replace(["'", '"'], '', $row['type']);
                $productionDateRaw = $row['production_date'];
                $productionDateArray = explode("__", $productionDateRaw);

                $day = $this->getDay($productionDateArray[0]);
                $month = $this->getMonth($productionDateArray[1]);
                $year = $this->getYear($productionDateArray[2]);

                $productionDate = "$year-$month-$day";

                $sql2 = "INSERT INTO Clothes (UUID, TYPE, PRODUCED_ON, PRODUCED_TURN, PRODUCED_BY) VALUES (:uuid, :type, :producedOn, :producedTurn, :producedBy)";
                $query2 = $connNew->prepare($sql2);
                $query2->bindParam(":uuid", $row['cloth_uuid']);
                $query2->bindParam(":type", $type);
                $query2->bindParam(":producedOn", $productionDate);
                $query2->bindParam(":producedTurn", $row['turn']);
                $query2->bindParam(":producedBy", $row['producer']);
                $query2->execute();
            }

        } catch (PDOException $e) {
            echo "Could not execute the SQL statement to transfer the Clothes table. Exception message: " . $e->getMessage();
        }
    }

    private function getDay(string $uuid)
    {
        $connOld = $this->db->getConnection();
        $query = $connOld->prepare("SELECT day FROM Days WHERE day_uuid = :uuid");
        $query->bindParam(":uuid", $uuid);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC)['day'];
    }

    private function getMonth(string $uuid)
    {
        $connOld = $this->db->getConnection();
        $query = $connOld->prepare("SELECT month FROM Months WHERE month_uuid = :uuid");
        $query->bindParam(":uuid", $uuid);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC)['month'];
    }

    private function getYear(string $uuid)
    {
        $connOld = $this->db->getConnection();
        $query = $connOld->prepare("SELECT year FROM Years WHERE year_uuid = :uuid");
        $query->bindParam(":uuid", $uuid);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC)['year'];
    }

    private function transferStores(): void
    {
        $connOld = $this->db->getConnection();
        $connNew = $this->db->getConnectionNew();

        $sql = "SELECT * FROM Stores";
        $query = $connOld->prepare($sql);

        try {
            if (!$query->execute()) {
                throw new PDOException("Could not execute the SQL statement");
            }

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $uuid = $row["store_uuid_1"] . "-" . $row["store_uuid_2"] . "-" .
                        $row["store_uuid_3"] . "-" . $row["store_uuid_4"] . "-" . $row["store_uuid_5"];

                $sql2 = "INSERT INTO Stores (UUID, NAME) VALUES (:uuid, :name)";
                $query2 = $connNew->prepare($sql2);
                $query2->bindParam(":uuid", $uuid);
                $query2->bindParam(":name", $row['keyword']);
                $query2->execute();
            }

        } catch (PDOException $e) {
            echo "Could not execute the SQL statement to transfer the Stores table. Exception message: " . $e->getMessage();
        }
    }

    private function transferDistributionLog(): void
    {
        $connOld = $this->db->getConnection();
        $connNew = $this->db->getConnectionNew();

        $sql = "SELECT * FROM Distribution_log";
        $query = $connOld->prepare($sql);

        try {
            if (!$query->execute()) {
                throw new PDOException("Could not execute the SQL statement");
            }

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $sql2 = "INSERT INTO Distribution_log (UUID, ORIGIN_STORE, DESTINATION_STORE, TRANSACTION_DATE, CLOTH) VALUES (:uuid, :originStore, :destinationStore, :transactionDate, :cloth)";
                $query2 = $connNew->prepare($sql2);
                $query2->bindParam(":uuid", $row['distribution_log_uuid']);
                $query2->bindParam(":originStore", $row['origin_store']);
                $query2->bindParam(":destinationStore", $row['destination_store']);
                $query2->bindParam(":transactionDate", $row['transaction_date']);
                $query2->bindParam(":cloth", $row['cloth_uuid']);
                $query2->execute();
            }

        } catch (PDOException $e) {
            echo "Could not execute the SQL statement to transfer the Distribution_log table. Exception message: " . $e->getMessage();
        }
    }
}