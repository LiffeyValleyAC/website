<?php
namespace LVAC\Test\Result;

use \Carbon\Carbon as c;

class ResultTest extends \PHPUnit_Framework_TestCase
{
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
}
