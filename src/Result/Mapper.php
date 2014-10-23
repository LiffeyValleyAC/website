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

    public function save(\LVAC\Result\Result $result)
    {
        $sql = "
            INSERT INTO results (name, duration, handicap, place, race_id) VALUES (?, ?, ?, ?, ?)
            ";
        $stmt = $this->conn->prepare($sql);
        $binds = array(
            $result->getName(),
            $result->getDuration()->format('PT%iM%sS'),
            $result->getHandicap()->format('PT%iM%sS'),
            $result->getPlace(),
            $result->getRaceId()
        );
        if ($stmt->execute($binds)) {
            return $result;
        }

        return false;
    }

    public function createResultFromRow($row)
    {
        $result = new Result();
        $result->setName($row['name']);
        $result->setDuration($row['duration']);
        $result->setHandicap($row['handicap']);
        $result->setPlace($row['place']);
        $result->setRaceId($row['race_id']);
        return $result;
    }
}
