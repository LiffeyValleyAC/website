<?php
namespace LVAC\Test\Race;

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

        for ($i = 0; $i < 10; $i++) {
            $race = new \LVAC\Race\Race();
            $race->setTitle($faker->sentence(rand(3, 10)));
            $race->setDate($faker->dateTimeBetween('now', '3 months')->format('Y-m-d H:i:s'));
            $race->setDescription($faker->paragraph(rand(3, 10)));
            $race->setSlug($race->createSlug($race->getTitle(), $race->getDate()));
            $race->setLatitude($faker->latitude());
            $race->setLongitude($faker->longitude());

            $race_mapper->save($race);
        }
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
}
