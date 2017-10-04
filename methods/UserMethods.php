<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 28/6/17
 * Time: 00:44
 */
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserMethods
{
    public static function login($username, $password)
    {
        if (($response = User::login($username, $password)) != false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setContent('Bad login');
            die($response->send());
        }

    }

}