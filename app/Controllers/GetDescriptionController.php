<?php

namespace App\Controllers;

use Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\RepositoryResolver;

class GetDescriptionController extends Controller
{
    public function getDescription(Request $request): Response
    {
        $type = $request->get('type');
        $id = $request->get('id');

        $repository = (new RepositoryResolver)->resolve($request->get($type));
        $description = $repository->getDescription($id);

        return response_json(compact('description'));
    }
}
