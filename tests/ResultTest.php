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
}
