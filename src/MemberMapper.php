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
            SELECT email, password FROM users WHERE email = ? LIMIT 1
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array($email));
        $row = $stmt->fetch();

        if (isset($row['password']) && password_verify($password, $row['password'])) {
            return $this->createUserFromRow($row);
        }

        return false;
    }

    public function createUserFromRow($row)
    {
        return $row['email'];
    }
}
