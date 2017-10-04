<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 4/10/17
 * Time: 00:23
 */
class Oculist
{
    public $id,$name, $lastname, $professional_code;
    private static $error;
    /**
     * Oculist constructor.
     * @param $name
     * @param $lastname
     * @param $professional_code
     */
    public function __construct($id,$name, $lastname, $professional_code)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->professional_code = $professional_code;
    }

    private static function mysqlConstructor($row)
    {
        if ($row == null) {
            return false;
        }
        return new Oculist($row['id'],$row['name'], $row['lastname'], $row['professional_code']);
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
        $result = Connect::getInstance()->fetchAll("SELECT * FROM oculist");
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
        $row = Connect::getInstance()->fetchRow("SELECT * FROM oculist WHERE id = ? ", [$id]);
        if (empty($row)) {
            self::$error = "No existe Oculista";
            return false;
        }
        return self::mysqlConstructor($row);
    }
    public static function getByCode($code)
    {
        $row = Connect::getInstance()->fetchRow("SELECT * FROM oculist WHERE professional_code = ? ", [$code]);
        if (empty($row)) {
            self::$error = "No existe Oculista";
            return false;
        }
        return self::mysqlConstructor($row);
    }

    public function save()
    {
        $exists = self::getByCode($this->professional_code);
        if ($exists) {
            self::$error = "Ya existe el oculista";
            return false;
        }
        $this->id = Connect::getInstance()->queryInsert("INSERT INTO oculist (`name`,lastname,professional_code) VALUES (?,?,?)", [ $this->name, $this->lastname, $this->professional_code]);
        if ($this->id != null) {
            return true;
        } else {
            $this->error = "Error al guardar el nuevo oculista. Llama a fede ;)";
            return false;
        }
    }

    public function updateOculistById($name, $lastname, $professional_code)
    {
        $this->name = $name;
        $this->lastname = $lastname;
        $this->professional_code = $professional_code;
    }

    public function edit()
    {
        return Connect::getInstance()->nonQuery("UPDATE oculist SET `name` = ?, lastname = ?, professional_code = ? WHERE id = ?", [$this->name, $this->lastname, $this->professional_code, $this->id]);
    }


}