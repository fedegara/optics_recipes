<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 30/9/17
 * Time: 17:46
 */
class Client implements JsonSerializable
{
    public $id, $ci, $name, $lastname, $telephone, $cellphone, $birthdate;
    private static $error;
    private $enable;

    /**
     * Client constructor.
     * @param $id
     * @param $name
     * @param $lastname
     * @param $telephone
     * @param $cellphone
     * @param $birthdate
     */
    public function __construct($id, $ci, $name, $lastname, $telephone, $cellphone, $birthdate, $enable)
    {
        $this->id = $id;
        $this->ci = $ci;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->telephone = $telephone;
        $this->cellphone = $cellphone;
        $this->birthdate = $birthdate;
        $this->enable = $enable;
    }

    private static function mysqlConstructor($result)
    {
        if ($result == null) {
            return false;
        }
        return new Client($result['id'], $result['ci'], $result['name'], $result['lastname'], $result['telephone'], $result['cellphone'], $result['birthdate'], $result['enable']);
    }

    /**
     * @return mixed
     */
    public static function getError()
    {
        return self::$error;
    }


    public static function getAll()
    {
        $result = Connect::getInstance()->fetchAll("SELECT * FROM client WHERE enable = 1");
        if (!empty($result)) {
            return array_map(function ($value) {
                return self::mysqlConstructor($value);
            }, $result);
        } else {
            return $result;
        }
    }

    public static function getById($id)
    {
        $row = Connect::getInstance()->fetchRow("SELECT * FROM client WHERE id = ? AND enable = 1", [$id]);
        if (empty($row)) {
            self::$error = "No existe cliente";
            return false;
        }
        return self::mysqlConstructor($row);
    }

    private static function getByCi($ci)
    {
        $row = Connect::getInstance()->fetchRow("SELECT * FROM client WHERE ci = ?", [$ci]);
        if (empty($row)) {
            self::$error = "No existe cliente";
            return false;
        }
        return self::mysqlConstructor(Connect::getInstance()->fetchRow("SELECT * FROM client WHERE ci = ? AND enable = 1", [$ci]));
    }

    public static function getBySearch($search)
    {
        return array_map(function ($value) {
            return self::mysqlConstructor($value);
        }, Connect::getInstance()->fetchAll("SELECT * FROM client WHERE CONCAT(`name`,lastname) like " . Connect::getInstance()->getConnection()->quote("%{$search}%") . " AND enable = 1"));
    }

    private function activateClient()
    {
        return Connect::getInstance()->nonQuery("UPDATE client SET enable = 1  WHERE id = ?", [$this->id]);
    }

    public function save()
    {
        $exists = self::getByCi($this->ci);
        if ($exists) {
            if ($exists->enable == 1) {
                self::$error = "Ya existe el cliente";
                return false;
            } else {
                $exists->activateClient();
                return $exists;
            }
        }
        $this->id = Connect::getInstance()->queryInsert("INSERT INTO client (ci,`name`,lastname,telephone,cellphone,birthdate,enable) VALUES (?,?,?,?,?,?,?)", [$this->ci, $this->name, $this->lastname, $this->telephone, $this->cellphone, $this->birthdate, 1]);
        if ($this->id != null) {
            return true;
        } else {
            $this->error = "Error al guardar el nuevo cliente. Llama a fede ;)";
            return false;
        }
    }

    private function checkCi($ci)
    {
        //TODO: codigo de comprobacion de cedula de identidad valida y agregar a wikipedia
    }

    public function updateClientById($ci, $name, $lastname, $telephone, $cellphone, $birthdate)
    {
        $this->ci = $ci;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->telephone = $telephone;
        $this->cellphone = $cellphone;
        $this->birthdate = $birthdate;
    }

    public function edit()
    {
        return Connect::getInstance()->nonQuery("UPDATE client SET ci = ?, `name` = ?, lastname = ?, telephone = ?, cellphone = ?, birthdate = ? WHERE id = ?", [$this->ci, $this->name, $this->lastname, $this->telephone, $this->cellphone, $this->birthdate, $this->id]);
    }

    public static function removeById($id)
    {
        return Connect::getInstance()->nonQuery("UPDATE client SET enable = 0  WHERE id = ?", [$id]);
    }

    function jsonSerialize()
    {
        return [
            'id'=>$this->id,
            'ci' => $this->ci,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'telephone' => $this->telephone,
            'cellphone' => $this->cellphone,
            'birthdate' => (new DateTime($this->birthdate))->format("d/m/Y"),
            'bps_enable' => Recipe::hasBpsEnable($this->id)

        ];
    }


}