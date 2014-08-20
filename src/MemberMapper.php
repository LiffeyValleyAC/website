<?php
namespace LVAC;
use \PDO;

class MemberMapper
{
    protected $conn;

    public function __construct($conn = null)
    {
        if ($conn !== null) {
            $this->conn = $conn;
        }
    }

    public function checkLogin($email, $password)
    {
        $sql = "
            SELECT id, email, password FROM users WHERE email = ? LIMIT 1
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array($email));
        $row = $stmt->fetch();

        if (isset($row['password']) && password_verify($password, $row['password'])) {
            return $this->createUserFromRow($row);
        }

        return false;
    }

    public function getMemberById($id)
    {
        $sql = "
            SELECT id, email, password, nickname FROM users WHERE id = :id LIMIT 1
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return $this->createUserFromRow($row);
    }

    public function createUserFromRow($row)
    {
        $member = new Member();
        $member->setId($row['id']);
        $member->setEmail($row['email']);
        if (array_key_exists('nickname', $row)) {
            $member->setNickname($row['nickname']);
        }
        return $member;
    }
}
