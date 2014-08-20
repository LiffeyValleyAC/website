<?php
namespace LVAC;

class Member
{
    protected $id;
    protected $email;
    protected $nickname;

    public function setId($id)
    {
        if (!$this->id) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    public function getNickname()
    {
        return $this->nickname;
    }
}
