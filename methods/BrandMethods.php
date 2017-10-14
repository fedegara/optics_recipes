<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 14/10/17
 * Time: 11:32
 */

use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\JsonResponse;

class BrandMethods extends BaseMethods
{
    public static function getAll()
    {
        if (($response = Brand::getAll()) !== false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall('Error al obtener las marcas');
        }
    }

    public static function create($name)
    {
        $brand = new Brand($name);
        if ($brand->save() == true) {
            (new JsonResponse())->setData($brand)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall("Error al guardar la marca");
        }
    }
}