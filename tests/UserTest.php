<?php
namespace LVAC\Test\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testUserObject()
    {
        $user = new \LVAC\User\User();
        $user->setName('Wile E. Coyote');

        $this->assertEquals('Wile E. Coyote', $user->getName());
    }
}
