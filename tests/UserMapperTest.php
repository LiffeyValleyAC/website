<?php
namespace LVAC\Test;

use \Mockery as m;

class UserMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadUserByUsername()
    {
        $user = array(
            'email' => 'bugs.bunny@acme.com',
            'password' => 'carrots'
        );
        $mock = m::mock('StdClass');
        $mock->shouldReceive('execute')->once()->andReturn(true);
        $mock->shouldReceive('fetch')->once()->andReturn($user);

        $db = m::mock('PDOMock');
        $db->shouldReceive('prepare')->once()->andReturn($mock);

        $mapper = new \LVAC\UserMapper($db);
        $result = $mapper->loadUserByUsername('bugs.bunny@acme.com');

        $this->assertInstanceOf('\LVAC\User', $result);
    }
}
