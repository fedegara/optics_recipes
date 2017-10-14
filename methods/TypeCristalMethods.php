<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 14/10/17
 * Time: 11:32
 */

use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\JsonResponse;

class TypeCristalMethods extends BaseMethods
{
    public static function getAll()
    {
        if (($response = TypeCristal::getAll()) !== false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall('Error al obtener las marcas');
        }
    }

    public static function create($name)
    {
        $type_cristal = new TypeCristal($name);
        if ($type_cristal->save() == true) {
            (new JsonResponse())->setData($type_cristal)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall("Error al guardar la marca");
        }
    }
}