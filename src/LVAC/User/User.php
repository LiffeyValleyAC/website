<?php
namespace LVAC\Users;

class Users {
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function GetName()
    {
        return $this->name;
    }
}
