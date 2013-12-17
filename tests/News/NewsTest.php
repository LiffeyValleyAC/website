<?php
namespace LVAC\NewsTest;

class NewsTest extends \PHPUnit_Framework_TestCase
{
    protected $db;
    public function __construct()
    {
        $this->db = new \PDO(
            'sqlite:LVAC.sqlite'
        );
    }
    public function testGetNewsReturnsTenNewsItems()
    {
        $news = new \LVAC\News\NewsMapper($this->db);
        $result = $news->getNews();

        $this->assertEquals(10, count($result));
    }
}
