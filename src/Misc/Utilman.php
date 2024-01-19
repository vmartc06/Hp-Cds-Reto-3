<?php

namespace Misc;

use PDO;
use PDOException;

class Utilman
{
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }
    public function showNonDuplicate()
    {
        $conn = $this->db->getConnectionNew();

        $sql = file_get_contents("src/Queries/utilman1.sql");
        $query = $conn->prepare($sql);

        try {
            if (!$query->execute()) {
                throw new PDOException("Could not execute the SQL statement");
            }

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            $uuids = [];
            $outArray = [];
            $storesData = [];

            foreach ($rows as $row) {
                if (!in_array($row['UUID'], $uuids)) {
                    $outArray[] = $row;
                    $uuids[] = $row['UUID'];
                    if (!isset($storesData[$row['DESTINATION']])) {
                        $storesData[$row['DESTINATION']] = 0;
                    }
                    $storesData[$row['DESTINATION']]++;
                }
            }

            asort($storesData);

            var_dump($storesData);

        } catch (PDOException $e) {
            echo "Could not execute the SQL statement to execute show non duplicates. Exception message: " . $e->getMessage();
        }
    }
}