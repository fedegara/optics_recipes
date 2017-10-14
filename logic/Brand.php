<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 14/10/17
 * Time: 11:23
 */
class Brand
{
    public $name;
    private static $error;

    /**
     * Brand constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public static function getError()
    {
        return self::$error;
    }

    /**
     *
     * @return Brand[]
     */
    public static function getAll()
    {
        $result = Connect::getInstance()->fetchAll("SELECT * FROM brand");
        if (!empty($result)) {
            return array_map(function ($value) {
                return new Brand($value['name']);
            }, $result);
        } else {
            return $result;
        }
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->name != null) {
            return Connect::getInstance()->nonQuery("INSERT INTO brand SET name = ?", [$this->name]);
        }
        return false;
    }
}