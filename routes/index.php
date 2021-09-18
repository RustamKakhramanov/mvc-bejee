<?php

$router = new Core\Router();

$router->add('', ['controller' => 'TasksController', 'action' => 'index']);

$router->dispatch($_SERVER['QUERY_STRING']);