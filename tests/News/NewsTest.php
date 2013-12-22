<?php
namespace LVAC\NewsTest;

use \Mockery as m;
use \Carbon\Carbon as c;

class NewsTest extends \PHPUnit_Framework_TestCase
{
    protected $db;
    public function __construct()
    {
        $this->db = new \PDO(
            'sqlite:LVAC.sqlite'
        );
    }

    public function testGetNewsReturnsTenNewsItemsByDefault()
    {
        $news = new \LVAC\News\NewsMapper($this->db);
        $result = $news->getNews();

        $this->assertEquals(10, count($result));
    }

    public function testGetNewsReturnsFiveNewsItemsIfAsked()
    {
        $news = new \LVAC\News\NewsMapper($this->db);
        $result = $news->getNews(5);

        $this->assertEquals(5, count($result));
    }

    public function testNewsModelHasSpecificAttributes()
    {
        $this->assertClassHasAttribute('title', '\LVAC\News\News');
        $this->assertClassHasAttribute('body', '\LVAC\News\News');
        $this->assertClassHasAttribute('date', '\LVAC\News\News');
        $this->assertClassHasAttribute('slug', '\LVAC\News\News');
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

    public function testDateIsSetToNowIfPassedANullValue()
    {
        c::setTestNow(c::createFromDate(2013, 12, 18));
        $expected = c::now();

        $news = new \LVAC\News\News();
        $news->setDate();
        $result = $news->getDate();

        $this->assertEquals($expected, $result);
    }

    public function testSlugGetsCreated()
    {
        $news = new \LVAC\News\News();
        $result = $news->createSlug('This is a title', '2013-12-22 12:00:00');

        $this->assertEquals('20131222-this-is-a-title', $result);
    }
}
