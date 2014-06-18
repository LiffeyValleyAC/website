<?php
namespace LVAC;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    protected $username;
    protected $password;

    public function getRoles()
    {
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }
}
