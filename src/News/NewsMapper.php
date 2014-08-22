<?php
namespace LVAC\News;
use \PDO;

class NewsMapper {
    protected $conn;

    public function __construct($conn = null)
    {
        if ($conn !== null) {
            $this->conn = $conn;
        }
    }

    public function getNews($limit = 10, $offset = 0)
    {
        $sql = "
            SELECT * FROM news ORDER BY date DESC LIMIT :limit OFFSET :offset
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $result = array();
        foreach ($rows as $row) {
            $result[] = $this->createNewsFromRow($row);
        }

        return $result;
    }

    public function getNewsBySlug($slug)
    {
        $sql = "
            SELECT * FROM news WHERE slug = ?
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array($slug));
        $row = $stmt->fetch();

        $result = $this->createNewsFromRow($row);

        return $result;
    }

    public function getPageOlderThan($page)
    {
        $sql = "
            SELECT COUNT(*) AS count FROM news
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row['count'] > ($page * 10)) {
            return $page + 1;
        }
        return false;
    }

    public function getPageNewerThan($page)
    {
        if ($page === 1) {
            return false;
        }
        return $page - 1;
    }

    public function save(\LVAC\News\News $news)
    {
        $sql = "
            INSERT INTO news (title, body, date, slug, location) VALUES (?, ?, ?, ?, ?)
            ";
        $stmt = $this->conn->prepare($sql);
        $binds = array(
            $news->getTitle(),
            $news->getBody(),
            $news->getDate(),
            $news->getSlug(),
            $news->getLocation()
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
        $news->setLocation($row['location']);
        return $news;
    }
}
