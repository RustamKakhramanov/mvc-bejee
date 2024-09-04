<?php


use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controllers\CitiesController;
use App\Controllers\CountryController;

$router = new Router();

$router->get('/', function(Request $request) {
    return (new CountryController)->index($request);
});

$router->run();
