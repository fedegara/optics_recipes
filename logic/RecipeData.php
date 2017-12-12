<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 14/10/17
 * Time: 15:58
 */
class RecipeData implements JsonSerializable
{
    public $id, $recipe_id, $close, $distance, $eye, $esf, $cil, $eje, $prisma, $disInt;

    /**
     * RecipeData constructor.
     * @param $id
     * @param $recipe_id
     * @param $close
     * @param $distance
     * @param $eye
     * @param $esf
     * @param $cil
     * @param $eje
     * @param $prisma
     * @param $disInt
     */
    public function __construct($id, $recipe_id, $close, $distance, $eye, $esf, $cil, $eje, $prisma, $disInt)
    {
        $this->id = $id;
        $this->recipe_id = $recipe_id;
        $this->close = $close;
        $this->distance = $distance;
        $this->eye = $eye;
        $this->esf = (is_null($esf) ? 0 : $esf);
        $this->cil = (is_null($cil) ? 0 : $cil);
        $this->eje = (is_null($eje) ? 0 : $eje);
        $this->prisma = (is_null($prisma) ? 0 : $prisma);
        $this->disInt = (is_null($disInt) ? 0 : $disInt);
    }


    public static function mysqlConstructor($row)
    {
        return new RecipeData(
            $row['id'],
            $row['recipe_id'],
            $row['close'],
            $row['distance'],
            $row['eye'],
            $row['esf'],
            $row['cil'],
            $row['eje'],
            $row['prisma'],
            $row['disInt']
        );
    }


    /**
     * @param $recipe_id
     * @return RecipeData[]
     */
    public static function getByRecipeId($recipe_id)
    {
        $sql = "SELECT * FROM recipe_data WHERE recipe_id = ? ";
        return array_map(function ($row) {
            return self::mysqlConstructor($row);
        }, Connect::getInstance()->fetchAll($sql, [$recipe_id]));
    }


    public function save()
    {
        $sql = "INSERT INTO recipe_data (recipe_id,close,distance,eye,esf,cil,eje,prisma,disInt) VALUES (?,?,?,?,?,?,?,?,?)";
        $this->id = Connect::getInstance()->queryInsert($sql, [$this->recipe_id, $this->close, $this->distance, $this->eye, $this->esf, $this->cil, $this->eje, $this->prisma, $this->disInt]);
        if ($this->id != null) {
            return true;
        } else {
            $this->error = "Error al guardar un nuevo recipe data. Llama a fede ;)";
            return false;
        }
    }

    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'recipe_id' => $this->recipe_id,
            'close' => boolval($this->close),
            'distance' => boolval($this->distance),
            'eye' => $this->eye,
            'esf' => $this->esf,
            'cil' => $this->cil,
            'eje' => $this->eje,
            'prisma' => $this->prisma,
            'disInt' => $this->disInt
        ];
    }


}