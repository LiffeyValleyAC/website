<?php
namespace LVAC\NewsTest;

use \Mockery as m;

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

    public function testSaveWorksWithMocking()
    {
        $mock = m::mock('StdClass');
        $mock->shouldReceive('execute')->once()->andReturn(true);

        $mock_db = m::mock('PDOMock');
        $mock_db->shouldReceive('prepare')->once()->andReturn($mock);

        $news_model = new \LVAC\News\News();

        $news = new \LVAC\News\NewsMapper($mock_db);
        $saved_news = $news->save($news_model);

        $this->assertNotEquals(false, $saved_news);
    }

    public function testSaveReturnsFalseIfThereIsAProblemWithSaving()
    {
        $mock = m::mock('StdClass');
        $mock->shouldReceive('execute')->once()->andReturn(false);

        $mock_db = m::mock('PDOMock');
        $mock_db->shouldReceive('prepare')->once()->andReturn($mock);

        $news_model = new \LVAC\News\News();

        $news = new \LVAC\News\NewsMapper($mock_db);
        $saved_news = $news->save($news_model);

        $this->assertFalse($saved_news);
    }
}
