<?php

/**
 * User
 *
 * A person or entity that can log in to the site
 */
class User
{
    /**
     * Unique identifier
     * @var integer
     */
    public $id;

    /**
     * Unique username
     * @var string
     */
    public $username;

    /**
     * Password
     * @var string
     */
    public $password;

    /**
     * Authenticate a user by username and password
     *
     * @param object $conn Connection to the database
     * @param string $username Username
     * @param string $password Password
     *
     * @return boolean True if the credentials are correct, null otherwise
     */
    public static function authenticate($conn, $username, $password)
    {
        $sql = "SELECT *
                FROM user
                WHERE user_name = :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return $user->password == $password;
        }
    }
    public static function isadminuser($conn, $username)
    {
        $sql = "SELECT *
                FROM adminuser
                WHERE user_name = :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return True;
        }
      }
        public static function isemployee($conn, $username)
        {
            $sql = "SELECT *
                    FROM employee
                    WHERE user_name = :username";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

            $stmt->execute();

            if ($user = $stmt->fetch()) {
                return True;
            }
          }
          public static function isvolunteer($conn, $username)
          {
                $sql = "SELECT *
                        FROM volunteer
                        WHERE user_name = :username";

                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':username', $username, PDO::PARAM_STR);

                $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

                $stmt->execute();

                if ($user = $stmt->fetch()) {
                    return True;
          }
    }
}
