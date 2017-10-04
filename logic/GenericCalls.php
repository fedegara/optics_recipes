<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 12/7/17
 * Time: 00:50
 */

use \Symfony\Component\HttpFoundation\Response;

class GenericCalls
{
    public static function makeCall($class, $method, $params)
    {
        //here we gonna order the params to the method received in right way
        $refm = new ReflectionMethod($class, $method);
        $params_to_call = [];
        foreach ($refm->getParameters() as $p) {
            if (!is_null($params[$p->getName()])) {
                $params_to_call[$p->getPosition()] = $params[$p->getName()];
            } else {
                $response = new Response();
                $response->setStatusCode(Response::HTTP_PRECONDITION_FAILED);
                $response->setContent($p->getName() . ' field cannot be empty');
                die($response->send());
            }

        }
        call_user_func_array([$class, $method], $params_to_call);
    }

    public static function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        return $d && $d->format('Y-m-d H:i:s') === $date;
    }

    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}