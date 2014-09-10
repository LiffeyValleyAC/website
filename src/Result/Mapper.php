<?php
namespace LVAC\Result;
use \PDO;

class Mapper {
    protected $conn;

    public function __construct($conn = null)
    {
        if ($conn !== null) {
            $this->conn = $conn;
        }
    }

    public function getResultsOfRace($raceid)
    {
        $sql = "
            SELECT * FROM results WHERE race_id = :raceid ORDER BY place
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':raceid', $raceid);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $result = array();
        foreach ($rows as $row) {
            $result[] = $this->createResultFromRow($row);
        }

        return $result;
    }

    public function createResultFromRow($row)
    {
        $result = new Result();
        $result->setName($row['name']);
        $result->setDuration($row['duration']);
        $result->setPlace($row['place']);
        return $result;
    }
}
