<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 7/11/17
 * Time: 22:44
 */

use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\JsonResponse;

class RecipeMethods extends BaseMethods
{
    public static function getAll()
    {
        if (($response = Recipe::getAll()) !== false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall(Recipe::getError());
        }
    }

    public static function create($date, $is_bps, $oculist_id, $client_id, $observations, array $recipe_datas)
    {
        $recipe = new Recipe(null, $date, $is_bps, $oculist_id, $client_id, $observations);
        $recipe->save();
        if (!is_null($recipe->id)) {
            foreach ($recipe_datas as $recipe_data) {
                $recipe_data = json_decode($recipe_data,true);
                $recipeData = new RecipeData(null, $recipe->id, $recipe_data['close'], $recipe_data['distance'], $recipe_data['eye'], $recipe_data['esf'], $recipe_data['cil'], $recipe_data['eje'], $recipe_data['prisma'], $recipe_data['disInt']);
                $recipeData->save();
                if (!is_null($recipeData->id)) {
                    $recipe->setRecipeData($recipeData);
                } else {
                    parent::errorCall("Error saving recipe data in method");
                }
            }
            if (count($recipe->getRecipeData()) == count($recipe_datas)) {
                (new JsonResponse())->setData($recipe)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
            }
        } else {
            parent::errorCall("Error saving recipe in method");
        }
        parent::errorCall("Error saving recipe in method");
    }

    public static function getById($recipe_id)
    {
        if (($response = Recipe::getById($recipe_id)) != false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall("Error geting recipe");
        }
    }

    public static function delete($recipe_id){
        if (Recipe::delete($recipe_id)) {
            (new JsonResponse())->setData("Recipe eliminado correctamente")->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall("Error deleting recipe");
        }
    }


    public static function getByClientId($client_id)
    {
        if (($response = Recipe::getByClientId($client_id)) != false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall("Error geting recipe of client");
        }
    }
}