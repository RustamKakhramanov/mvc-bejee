<?php


namespace App\Controllers;


use App\Models\Task;
use Core\View;
use Symfony\Component\HttpFoundation\Request;

class TasksController extends \Core\Controller
{

    public function index(Request $request)
    {
        $tasks = (new Task())->getWithPaginateAndSorting(
            $request->get('per_page'),
            $request->get('page_limit', 2),
            'name'
        );

        View::render('tasks/index.php', ['data' => $tasks['data'], 'pages' => $tasks['pages']]);
    }

    public function store(Request $request)
    {
        $validation = $this->validator->validate($request->request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'text' => 'required'
        ]);

        if ($validation->fails()) {
           return response_json($validation->errors()->toArray(), 400);
        }

        $task = (new Task())->createAndGet($validation->getValidData());

        return response_json($task, 201);
    }

}
