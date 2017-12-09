<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 30/9/17
 * Time: 17:30
 */

use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\JsonResponse;

class ClientsMethods extends BaseMethods
{
    public static function getAll()
    {
        if (($response = Client::getAll()) !== false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall('Error al obtener los clientes');
        }
    }

    public static function getById($client_id)
    {
        if (($response = Client::getById($client_id)) != false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall(Client::getError());
        }
    }

    public static function getBySearch($search)
    {
        if (($response = Client::getBySearch($search)) != false) {
            (new JsonResponse())->setData($response)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall("Error al obtener el cliente");
        }
    }

    public static function create($ci, $name, $lastname, $telephone, $cellphone, $birthdate)
    {
        $client = new Client(null, $ci, $name, $lastname, $telephone, $cellphone, $birthdate,1);
        if ($client->save()==true) {
            (new JsonResponse())->setData($client)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall(Client::getError());
        }
    }

    public static function edit($client_id, $ci, $name, $lastname, $telephone, $cellphone, $birthdate)
    {;
        $client = Client::getById($client_id);
        if($client){
            $client->updateClientById($ci, $name, $lastname, $telephone, $cellphone, $birthdate);
            if ($client->edit()) {
                (new JsonResponse())->setData($client)->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
            } else {
                parent::errorCall(Client::getError());
            }
        }else{
            parent::errorCall(Client::getError());
        }
    }

    public static function delete($client_id)
    {
        if (Client::removeById($client_id)) {
            (new JsonResponse())->setData("Client eliminado correctamente")->setStatusCode(RedirectResponse::HTTP_ACCEPTED)->send();
        } else {
            parent::errorCall(Client::getError());
        }
    }
}