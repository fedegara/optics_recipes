<?php

class Connect
{
    private static $instance = null;
    private $connection = null;


    public static function getInstance()
    {
        if (!self::$instance instanceof Connect) {
            self::$instance = new Connect();
        }
        return self::$instance;
    }

    /**
     * Connect constructor.
     * return PDO
     */
    private function connect()
    {
        if ($this->connection == null) {
            $this->connection = new PDO('mysql:host=localhost;dbname=db_optics;charset=utf8', 'root', DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
        return $this->connection;
    }

    /**
     * @return null|PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }




    /**
     * @param $sql
     * @param null $params
     * @return array
     */
    public function fetchAll($sql, $params = null)
    {
        return $this->prepareQuery($sql, $params)->fetchAll();
    }

    /**
     * @param $sql
     * @param null $params
     * @return array
     */
    public function fetchRow($sql, $params = null)
    {
        return $this->prepareQuery($sql, $params)->fetch();

    }

    /**
     * @param $sql
     * @param $params
     * @return bool
     */
    public function nonQuery($sql, $params)
    {
        return $this->prepareQuery($sql, $params)->rowCount() > 0;
    }

    public function queryInsert($sql, $params)
    {
        $this->prepareQuery($sql, $params);
        return $this->connection->lastInsertId();
    }

    private function prepareQuery($sql, $params = null)
    {
        if (empty($sql)) {
            throw new Exception("Query cannot be empty");
        }
        $return = $this->connection->prepare($sql);
        if (!is_null($params) and is_array($params) and !empty($params)) {
            $return->execute($params);
        }else{
            $return->execute();
        }
        $return->setFetchMode(PDO::FETCH_ASSOC);
        return $return;


    }

}