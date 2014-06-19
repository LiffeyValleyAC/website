<?php
namespace LVAC\Test;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testUserObject()
    {
        $user = new \LVAC\User();
        $user->setUsername('Wile E. Coyote');

        $this->assertEquals('Wile E. Coyote', $user->getUsername());
    }
}
