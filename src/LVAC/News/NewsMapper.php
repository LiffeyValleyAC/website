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

    public function getNews($limit = 10)
    {
        $sql = "
            SELECT * FROM news LIMIT ?
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array($limit));
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
            INSERT INTO news (title, body, date, slug) VALUES (?, ?, ?, ?)
            ";
        $stmt = $this->conn->prepare($sql);
        $binds = array(
            $news->getTitle(),
            $news->getBody(),
            $news->getDate(),
            $news->getSlug()
        );
        if ($stmt->execute($binds)) {
            return $news;
        }

        return false;
    }

    public function createNewsFromRow($row)
    {
        $news = new News();
        $news->setTitle($row['title']);
        $news->setBody($row['body']);
        $news->setDate($row['date']);
        $news->setSlug($row['slug']);
        return $news;
    }
}
