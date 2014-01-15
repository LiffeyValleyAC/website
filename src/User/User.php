<?php
namespace LVAC\User;

class User {
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
