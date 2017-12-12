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
            setcookie("user_token", $response->getToken(), time() + 60 * 60 * 24 * 1);
            header("Location: ".WEB_PATH."clients/");
            die();
        } else {
            header("Location: ".WEB_PATH."?error=Usuario o clave incorrectos");
            die();
        }

    }

}