<?php
namespace LVAC\Test\Users;

class UsersTest extends \PHPUnit_Framework_TestCase
{
    public function testUserObject()
    {
        $user = new \LVAC\Users\Users();
        $user->setName('Wile E. Coyote');

        $this->assertEquals('Wile E. Coyote', $user->getName());
    }
}
