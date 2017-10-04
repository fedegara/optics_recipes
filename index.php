<?php
cors();
require 'vendor/autoload.php';
include_once 'config/config_site.php';
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use Symfony\Component\HttpFoundation\Response;

$router = new AltoRouter();
//$router->setBasePath('/faltauno');
$router->setBasePath(SITE_FOLDER);
$router->map('POST', '/login', ['class' => 'UserMethods', 'method' => 'login', 'need_login' => false]);

//Clients
$router->map('GET', '/clients', ['class' => 'ClientsMethods', 'method' => 'getAll', 'need_login' => true]);
$router->map('GET', '/clients/[i:client_id]', ['class' => 'ClientsMethods', 'method' => 'getById', 'need_login' => true]);
$router->map('GET', '/clients/search/[a:search]', ['class' => 'ClientsMethods', 'method' => 'getBySearch', 'need_login' => true]);
$router->map('POST', '/clients', ['class' => 'ClientsMethods', 'method' => 'create', 'need_login' => true]);
$router->map('PUT', '/clients/[i:client_id]', ['class' => 'ClientsMethods', 'method' => 'edit', 'need_login' => true]);
$router->map('DELETE', '/clients/[i:client_id]', ['class' => 'ClientsMethods', 'method' => 'delete', 'need_login' => true]);

$router->map('GET', '/oculist', ['class' => 'OculistMethods', 'method' => 'getAll', 'need_login' => true]);
$router->map('GET', '/oculist/[i:oculist_id]', ['class' => 'OculistMethods', 'method' => 'getById', 'need_login' => true]);
$router->map('POST', '/oculist', ['class' => 'OculistMethods', 'method' => 'create', 'need_login' => true]);
$router->map('PUT', '/oculist/[i:oculist_id]', ['class' => 'OculistMethods', 'method' => 'edit', 'need_login' => true]);


$router->map('GET', '/', ['html' => 'index.html']);
$router->map('GET', '/admin/', ['html' => 'admin.html']);


// match current request
$match = $router->match();
$params = [];
if (isset($match['target']['html'])) {
    die(file_get_contents('views/' . $match['target']['html']));
}

if ($match['target']['need_login'] === true) {
    $user = User::checkUser(filter_input(INPUT_SERVER, 'HTTP_APIKEY'));
    if (!is_object($user)) {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        $response->setContent('Token not valid');
        die($response->send());
    }
}
if ($match) {
    $input = file_get_contents("php://input");
    if (GenericCalls::isJson($input)) {
        $input = json_decode($input, true);
    } else {
        parse_str($input, $input);
    }

    if (!empty($input) or !empty($_POST)) {
        if (!empty($input)) {
            $params = array_merge($params, $input);
        } else {
            $params = array_merge($params, $_POST);
        }
        $params = array_merge($params, $match['params']);
    } else {
        $params = array_merge($params, $match['params']);
    }
    if (isset($match['target']['need_login']) and $match['target']['need_login'] === true) {
        $params = array_merge($params, ['user' => $user]);
    }
    GenericCalls::makeCall($match['target']['class'], $match['target']['method'], $params);
} else {
    // no route was matched
    $response = new Response();
    $response->setStatusCode(Response::HTTP_NOT_FOUND);
    $response->setContent('Invalid URL');
    die($response->send());
}


function cors()
{

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}