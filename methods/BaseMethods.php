<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 3/10/17
 * Time: 22:01
 */

use Symfony\Component\HttpFoundation\Response;

class BaseMethods
{
    protected static function errorCall($msg = null)
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $response->setContent(($msg == null) ? "Error en el llamado" : $msg);
        die($response->send());
    }
}