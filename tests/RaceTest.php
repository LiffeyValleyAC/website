<?php
namespace LVAC\Test\Race;

use \LVAC\Slug;
use \Carbon\Carbon as c;

class RaceTest extends \PHPUnit_Framework_TestCase
{
    protected $db;
    public function __construct()
    {
        $this->db = new \PDO(
            'sqlite::memory:'
        );
        $sql = "CREATE TABLE races
            (
                id INTEGER PRIMARY KEY ASC,
                title TEXT,
                date,
                description TEXT,
                slug TEXT,
                latitude TEXT,
                longitude TEXT,
                report TEXT
            )";
        $this->db->exec($sql);

        $race_mapper = new \LVAC\Race\Mapper($this->db);
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $race = new \LVAC\Race\Race();
            $race->setTitle($faker->sentence(rand(3, 10)));
            $race->setDate($faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d H:i:s'));
            $race->setDescription($faker->paragraph(rand(3, 10)));
            $race->setSlug(Slug::create($race->getTitle(), $race->getDate()));
            $race->setLatitude($faker->latitude());
            $race->setLongitude($faker->longitude());

            $race_mapper->save($race);
        }

        for ($i = 0; $i < 20; $i++) {
            $race = new \LVAC\Race\Race();
            $race->setTitle($faker->sentence(rand(3, 10)));
            $race->setDate($faker->dateTimeBetween('now', '3 months')->format('Y-m-d H:i:s'));
            $race->setDescription($faker->paragraph(rand(3, 10)));
            $race->setSlug(Slug::create($race->getTitle(), $race->getDate()));
            $race->setLatitude($faker->latitude());
            $race->setLongitude($faker->longitude());

            $race_mapper->save($race);
        }
    }

    public function testGetResultsReturnsTenRaceItemsByDefault()
    {
        $race = new \LVAC\Race\Mapper($this->db);
        $result = $race->getResults();

        $this->assertEquals(10, count($result));
    }

    public function testGetResultsReturnsFiveRaceItemsIfAsked()
    {
        $race = new \LVAC\Race\Mapper($this->db);
        $result = $race->getResults(5);

        $this->assertEquals(5, count($result));
    }

    public function testGetFutureRacesReturnsTenRaceItemsByDefault()
    {
        $race = new \LVAC\Race\Mapper($this->db);
        $result = $race->getFutureRaces();

        $this->assertEquals(10, count($result));
    }

    public function testGetFutureRacesReturnsFiveRaceItemsIfAsked()
    {
        $race = new \LVAC\Race\Mapper($this->db);
        $result = $race->getFutureRaces(5);

        $this->assertEquals(5, count($result));
    }

    public function testGetRaceBySlug()
    {
        $faker = \Faker\Factory::create();
        $mapper = new \LVAC\Race\Mapper($this->db);

        $expected = new \LVAC\Race\Race();
        $expected->setTitle('Annual 10k');
        $expected->setDate('2014-06-06 12:00:00');
        $expected->setSlug('20140606-annual-10k');

        $mapper->save($expected);

        $result = $mapper->getRaceBySlug('20140606-annual-10k');

        $this->assertEquals($expected->getTitle(), $result->getTitle());
    }

    public function testGetMapReturnsAnArrayWithCoordinates()
    {
        $race = new \LVAC\Race\Mapper($this->db);
        $result = $race->getFutureRaces(1);
        $map = $result[0]->getMap();

        $this->assertTrue(array_key_exists('latitude', $map));
        $this->assertTrue(array_key_exists('longitude', $map));
    }

    public function testDateIsSetToNowIfPassedANullValue()
    {
        c::setTestNow(c::createFromDate(2013, 12, 18));
        $expected = c::now();

        $race = new \LVAC\Race\Race();
        $race->setDate();
        $result = $race->getDate();

        $this->assertEquals($expected, $result);
    }
}
