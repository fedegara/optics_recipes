<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 3/10/17
 * Time: 23:51
 */
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\JsonResponse;

class OculistMethods extends BaseMethods
{

    public static function getAll()
    {
        if (($response = Oculist::getAll()) !== false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall('Error al obtener los oculistas');
        }
    }

    public static function getById($oculist_id)
    {
        if (($response = Oculist::getById($oculist_id)) != false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall(Oculist::getError());
        }
    }

    public static function create($name,$lastname, $professional_code)
    {
        $oculist = new Oculist(null, $name, $lastname, $professional_code);
        if ($oculist->save() == true) {
            (new JsonResponse())->setData($oculist)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall(Oculist::getError());
        }
    }

    public static function edit($oculist_id, $name, $lastname, $professional_code)
    {
        ;
        $oculist = Oculist::getById($oculist_id);
        if ($oculist) {
            $oculist->updateOculistById($name, $lastname, $professional_code);
            if ($oculist->edit()) {
                (new JsonResponse())->setData($oculist)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
            } else {
                parent::errorCall(Oculist::getError());
            }
        } else {
            parent::errorCall(Oculist::getError());
        }
    }

}