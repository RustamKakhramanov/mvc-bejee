<?php


use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$router = new \Buki\Router\Router([
    'base_folder' => realpath('../'),
    'paths' => [
        'controllers' => 'App/Controllers',
        'middlewares' => 'App/Middlewares',
    ],
    'namespaces' => [
        'controllers' => 'App\Controllers',
        'middlewares' => 'App\Middlewares',
    ]
]);

$router->get('/', 'TasksController@index');



$router->run();