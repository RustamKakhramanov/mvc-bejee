<?php


namespace App\Controllers;


use App\Models\Task;
use Core\View;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\Request;

class TasksController extends \Core\Controller
{

    public function index(Request $request)
    {
        $task = new Task();

        var_export($task->getAll());
        exit();
    }

    public function createAction() {
        $validation = $this->validator->make($_POST + $_FILES, [
            'name'                  => 'required|string',
            'email'                 => 'required|email',
            'text'                 => 'required|string',
        ]);
    }

}
