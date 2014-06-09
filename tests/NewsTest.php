<?php
namespace LVAC\Test\News;

use \Mockery as m;
use \Carbon\Carbon as c;

class NewsTest extends \PHPUnit_Framework_TestCase
{
    protected $db;
    public function __construct()
    {
        $this->db = new \PDO(
            'sqlite::memory:'
        );
        $sql = "CREATE TABLE news
            (
                id INTEGER PRIMARY KEY ASC,
                title TEXT,
                body TEXT,
                date,
                slug TEXT,
                location TEXT
            )";
        $this->db->exec($sql);

        $news_mapper = new \LVAC\News\NewsMapper($this->db);
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $news = new \LVAC\News\News();
            $news->setTitle($faker->sentence(rand(3, 10)));
            $news->setBody($faker->paragraph(rand(3, 10)));
            $news->setDate($faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d H:i:s'));
            $news->setSlug($news->createSlug($news->getTitle(), $news->getDate()));
            $news->setLocation($faker->streetName());

            $news_mapper->save($news);
        }
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
        $this->assertClassHasAttribute('location', '\LVAC\News\News');
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

    public function testGetNewsBySlug()
    {
        $faker = \Faker\Factory::create();
        $mapper = new \LVAC\News\NewsMapper($this->db);

        $expected = new \LVAC\News\News();
        $expected->setTitle('My lovely horse');
        $expected->setBody($faker->paragraph(rand(3, 10)));
        $expected->setDate('2014-06-06 12:00:00');
        $expected->setSlug();
        $expected->setLocation($faker->streetName());

        $mapper->save($expected);

        $result = $mapper->getNewsBySlug('20140606-my-lovely-horse');

        $this->assertEquals($expected->getTitle(), $result->getTitle());
    }
}
