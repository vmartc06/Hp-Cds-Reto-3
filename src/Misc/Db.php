<?php

namespace Misc;

use Exception;
use PDO;
use PDOException;

class Db
{
    private PDO $conn;
    private PDO $connNew;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $host = "localhost";
        $user = "root";
        $password = "toor";
        $dbname = "cds";
        $dbnameNew = "new_cds";
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $this->connNew = new PDO("mysql:host=$host;dbname=$dbnameNew", $user, $password);
        } catch (PDOException $e) {
            throw new Exception("Could not connect to the database", $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }

    public function getConnectionNew(): PDO
    {
        return $this->connNew;
    }
}