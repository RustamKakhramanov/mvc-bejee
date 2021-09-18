<?php


namespace App\Controllers;


use Core\View;

class TasksController extends \Core\Controller
{

    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }
}
