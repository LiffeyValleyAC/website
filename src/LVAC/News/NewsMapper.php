<?php
namespace LVAC\News;

class NewsMapper {
    protected $conn;

    public function __construct($conn = null)
    {
        if ($conn !== null) {
            $this->conn = $conn;
        }
    }

    public function getNews()
    {
        $sql = "
            SELECT * FROM news LIMIT 10
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $result = array();
        foreach ($rows as $row) {
            $result[] = $this->createNewsFromRow($row);
        }

        return $result;
    }

    public function save(\LVAC\News\News $news)
    {
        $sql = "
            INSERT INTO news (title, body, date) VALUES (?, ?, ?)
            ";
        $stmt = $this->conn->prepare($sql);
        $binds = array(
            $news->getTitle(),
            $news->getBody(),
            $news->getDate()
        );
        $stmt->execute($binds);
    }

    public function createNewsFromRow($row)
    {
        $news = new News();
        return $news;
    }
}
