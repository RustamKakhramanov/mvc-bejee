<?php


use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controllers\CitiesController;

$router = new Router();

$router->get('/', function(Request $request) {
    return (new CitiesController)->index($request);
});

$router->run();
