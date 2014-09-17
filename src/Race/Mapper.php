<?php
namespace LVAC\Race;

use \PDO;
use \Carbon\Carbon as c;

class Mapper {
    protected $conn;

    public function __construct($conn = null)
    {
        if ($conn !== null) {
            $this->conn = $conn;
        }
    }

    public function getResults($limit = 10, $offset = 0)
    {
        $sql = "
            SELECT * FROM races WHERE date < NOW() ORDER BY date DESC LIMIT :limit OFFSET :offset
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $result = array();
        foreach ($rows as $row) {
            $result[] = $this->createRaceFromRow($row);
        }

        return $result;
    }

    public function getFutureRaces($limit = 10, $offset = 0)
    {
        $sql = "
            SELECT * FROM races WHERE date >= :now ORDER BY date LIMIT :limit OFFSET :offset
            ";
        $stmt = $this->conn->prepare($sql);

        $now = c::now();
        $stmt->bindParam(':now', $now);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $result = array();
        foreach ($rows as $row) {
            $result[] = $this->createRaceFromRow($row);
        }

        return $result;
    }

    public function getRaceBySlug($slug)
    {
        $sql = "
            SELECT * FROM races WHERE slug = ?
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array($slug));
        $row = $stmt->fetch();

        $result = $this->createRaceFromRow($row);

        return $result;
    }

    public function groupByYear($races)
    {
        $ordered = array();
        foreach ($races as $race) {
            $year = strftime("%Y", strtotime($race->getDate()));
            $ordered[$year][] = $race;
        }
        return $ordered;
    }

    public function save(\LVAC\Race\Race $race)
    {
        $sql = "
            INSERT INTO races (title, date, description, slug, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)
            ";
        $stmt = $this->conn->prepare($sql);
        $binds = array(
            $race->getTitle(),
            $race->getDate(),
            $race->getDescription(),
            $race->getSlug(),
            $race->getLatitude(),
            $race->getLongitude()
        );
        if ($stmt->execute($binds)) {
            return $race;
        }

        return false;
    }

    public function createRaceFromRow($row)
    {
        $race = new Race();
        $race->setId($row['id']);
        $race->setTitle($row['title']);
        $race->setSlug($row['slug']);
        $race->setDate($row['date']);
        $race->setDescription($row['description']);
        $race->setReport($row['report']);
        $race->setMap($row['latitude'], $row['longitude']);
        return $race;
    }
}
