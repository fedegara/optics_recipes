<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 16/6/17
 * Time: 19:32
 */
class User implements JsonSerializable
{
    private $id, $username, $name, $lastname, $token;

    /**
     * User constructor.
     * @param $email
     * @param $name
     * @param $first_name
     * @param $password
     */
    public function __construct($id, $username, $name, $token, $lastname)
    {
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->token = $token;
        $this->lastname = $lastname;

    }

    private static function mysqlConstructor($result)
    {
        return new User($result['id'], $result['username'], $result['name'], $result['token'], $result['lastname']);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }


    public static function checkUser($token)
    {
        $sql = "SELECT * FROM `user` WHERE token = ?";
        $result = Connect::getInstance()->fetchRow($sql, [$token]);
        $user = false;
        if ($result != false) {
            $user = self::mysqlConstructor($result);
        }
        return $user;
    }

    public static function getById($id, $without_token = false)
    {
        $sql = "SELECT * FROM `user` WHERE id = ?";
        $result = Connect::getInstance()->fetchRow($sql, [$id]);
        $user = false;
        if ($result != false) {
            if ($without_token) {
                $result['token'] = null;
            }
            $user = self::mysqlConstructor($result);
        }
        return $user;
    }

    /**
     * @param $email
     * @param $password
     * @return bool|User
     */
    public static function login($email, $password)
    {
        $sql = "SELECT * FROM `user` WHERE username = ? AND password = MD5(?)";
        $result = Connect::getInstance()->fetchRow($sql, [$email, $password]);
        $user = false;
        if ($result != false) {
            $user = self::mysqlConstructor($result);
        }
        if ($user != false) {
            $user->generateToken();
            Connect::getInstance()->nonQuery('UPDATE `user` SET token = ? WHERE id = ?', [$user->getToken(), $user->getId()]);
        }
        return $user;
    }

    private function generateToken()
    {
        $this->token = md5($this->username . strtotime('now') . $this->id) . "_" . $this->parseExpirationDate();
    }

    private function parseExpirationDate($desParse = false)
    {
        $values = ['2', '3', '6'];
        $code = ['d_e', 'a$f', '1_4'];
        if ($desParse === false) {
            return str_replace($values, $code, strtotime("+1 week"));
        } else {
            return str_replace($code, $values, $this->token);
        }
    }


    function jsonSerialize()
    {
        $return = [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'lastname' => $this->lastname,
        ];
        if (!is_null($this->token)) {
            $return['token'] = $this->token;
        }
        return $return;
    }

}