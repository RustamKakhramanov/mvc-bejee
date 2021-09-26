<?php

namespace Core;

use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\Request;

/** @property Validator $validator */
abstract class Controller
{
    protected Validator $validator;
    protected Request $request;

    public function __construct()
    {
        $this->validator = new Validator();

        $this->before();
    }

    protected function before()
    {
    }

    protected function after()
    {
    }

    public function __destruct()
    {
        $this->after();

    }
}
