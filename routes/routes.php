<?php


use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controllers\CitiesController;
use App\Controllers\CountryController;
use App\Controllers\GetDescriptionController;

$router = new Router();

$router->get('/', function (Request $request) {
    return (new CountryController)->index($request);
});

// $router->get('/', function(Request $request) {
//     return (new GetDescriptionController)->getDescription($request);
// });

$router->run();
