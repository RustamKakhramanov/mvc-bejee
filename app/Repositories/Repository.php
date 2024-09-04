<?php

namespace App\Repositories;

/**
 * @property Model $model
 */
abstract class Repository
{
    public function __construct()
    {
        $class = $this->getModelClass();

        $this->model = new $class;
    }

    abstract protected function getModelClass(): string;
}
