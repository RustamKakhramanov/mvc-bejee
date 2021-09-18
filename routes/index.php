<?php

$router = new Core\Router();

$router->get('', ['controller' => 'TasksController', 'action' => 'index']);

$router->dispatch($_SERVER['QUERY_STRING']);