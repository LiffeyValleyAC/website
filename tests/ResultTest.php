<?php
namespace LVAC\Test\Result;

use \Carbon\Carbon as c;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    protected $db;
    public function setUpRace()
    {
        $this->db = new \PDO(
            'sqlite::memory:'
        );
        $sql = "CREATE TABLE results
            (
                id INTEGER PRIMARY KEY ASC,
                name TEXT,
                duration TEXT,
                handicap TEXT,
                place INTEGER,
                race_id INTEGER
            )";
        $this->db->exec($sql);

        $result_mapper = new \LVAC\Result\Mapper($this->db);
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $result = new \LVAC\Result\Result();
            $result->setName($faker->name);
            $result->setDuration('PT'.$faker->numberBetween(0,59).'M'.$faker->numberBetween(0,59).'S');
            $result->setHandicap('PT'.$faker->numberBetween(0,9).'M'.$faker->numberBetween(0,59).'S');
            $result->setPlace($faker->numberBetween(1,20));
            $result->setRaceId(1);

            $result_mapper->save($result);
        }
    }

    public function testGetDuration()
    {
        $model = new \LVAC\Result\Result();
        $model->setDuration('PT1M4S');

        $result = $model->getDuration();

        $this->assertInstanceOf('DateInterval', $result);
    }

    public function testGetHandicapReturnsGuest()
    {
        $model = new \LVAC\Result\Result();
        $model->setHandicap('guest');

        $result = $model->getHandicap();

        $this->assertEquals('guest', $result);
    }

    public function testGetHandicapReturnsADateInterval()
    {
        $model = new \LVAC\Result\Result();
        $model->setHandicap('PT1M4S');

        $result = $model->getHandicap();

        $this->assertNotEquals('guest', $result);
        $this->assertInstanceOf('DateInterval', $result);
    }

    public function testGetNettReturnsBlankForAGuest()
    {
        $model = new \LVAC\Result\Result();
        $model->setHandicap('guest');

        $result = $model->getNett();

        $this->assertEquals('', $result);
    }

    public function testGetNettReturnsTheDifferenceBetweenDurationAndHandicap()
    {
        $model = new \LVAC\Result\Result();
        $model->setDuration('PT10M4S');
        $model->setHandicap('PT1M4S');

        $result = $model->getNett();

        $this->assertEquals('09m 00s', $result);
    }

    public function testGG()
    {
        $this->setUpRace();
        $mapper = new \LVAC\Result\Mapper($this->db);
        $result = $mapper->getResultsOfRace(1);

        $this->assertEquals(20, count($result));
    }
}
