<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 14/10/17
 * Time: 15:44
 */
class Recipe implements JsonSerializable
{
    public $id, $date, $is_bps, $observations;
    private $oculist, $client, $recipe_data;
    private static $error;

    /**
     * @return mixed
     */
    public static function getError()
    {
        return self::$error;
    }

    /**
     * Recipe constructor.
     * @param $id
     * @param $date
     * @param $is_bps
     * @param $oculist
     * @param $client
     * @param $observations
     */
    public function __construct($id, $date, $is_bps, $oculist, $client, $observations)
    {
        $this->id = $id;
        $this->date = $date;
        $this->is_bps = $is_bps;
        $this->oculist = $oculist;
        $this->client = $client;
        $this->observations = $observations;
    }

    /**
     * @return RecipeData[]
     */
    public function getRecipeData()
    {
        if (empty($this->recipe_data) || $this->recipe_data == null) {
            $this->recipe_data = RecipeData::getByRecipeId($this->id);
        }
        return $this->recipe_data;
    }

    /**
     * @param RecipeData $recipe_data
     */
    public function setRecipeData($recipe_data)
    {
        if (count($this->recipe_data) > 4) {
            throw new Exception("Saving more than 4 rows for recipe something is wrong men!!!");
        }
        $this->recipe_data[] = $recipe_data;
    }


    /**
     * @param $row
     * @return Recipe
     */
    private static function mysqlConstruct($row)
    {
        return new Recipe($row['id'], $row['date'], $row['bps'], $row['oculist_id'], $row['client_id'], $row['observations']);
    }

    /**
     * @return Oculist
     */
    public function getOculist()
    {
        if (!is_object($this->oculist)) {
            $this->oculist = Oculist::getById($this->oculist);
        }
        return $this->oculist;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (!is_object($this->client)) {
            $this->client = Client::getById($this->client);
        }
        return $this->client;
    }

    /**
     * @return Recipe[]
     */
    public static function getAll()
    {
        return array_map(function ($value) {
            return self::mysqlConstruct($value);
        }, Connect::getInstance()->fetchAll("SELECT * FROM recipe"));
    }

    public static function getById($id)
    {
        $recipe = self::mysqlConstruct(Connect::getInstance()->fetchRow("SELECT * FROM recipe WHERE id = ?", [$id]));
        $recipe->recipe_data = RecipeData::getByRecipeId($recipe->id);
        return $recipe;
    }

    public static function getByClientId($client_id)
    {
        return array_map(function ($value) {
            return self::mysqlConstruct($value);
        }, Connect::getInstance()->fetchAll("SELECT * FROM recipe WHERE client_id = ?", [$client_id]));
    }

    public function save()
    {
        $sql = "INSERT INTO recipe (date,bps,oculist_id,client_id,observations) VALUES (?,?,?,?,?)";
        $this->id = Connect::getInstance()->queryInsert($sql, [$this->date, $this->is_bps, $this->getOculist()->id, $this->getClient()->id, $this->observations]);
        if ($this->id != null) {
            return true;
        } else {
            $this->error = "Error al guardar una nueva receta. Llama a fede ;)";
            return false;
        }
    }

    public static function delete($id)
    {
        $sql = "DELETE FROM recipe WHERE id = ?";
        return count(Connect::getInstance()->nonQuery($sql, [$id])) > 0;
    }

    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'date' => (new DateTime($this->date))->format("d/m/Y"),
            'is_bps' =>boolval($this->is_bps),
            'observation' => $this->observations,
            'oculist' => $this->getOculist(),
            'client' => $this->getClient(),
            'recipe_data' => $this->getRecipeData()
            //'bps_enable' =>
        ];
    }


}