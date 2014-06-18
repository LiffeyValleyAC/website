<?php
namespace LVAC;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserMapper implements UserProviderInterface
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function loadUserByUsername($username)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = ?";
            $statement = $this->conn->prepare($sql);
            $statement->execute(array($username));
            $row = $statement->fetch();

            if (false === $row) {
                throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
            }

            return $this->createUserFromRow($row);
        } catch (\Exception $e) {
            echo "Uh oh: ", $e->getMessage();
        }
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof \LVAC\User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === '\LVAC\User';
    }

    public function createUserFromRow($row)
    {
        $user = new \LVAC\User();
        $user->setUsername($row['email']);
        $user->setPassword($row['password']);

        return $user;
    }
}
