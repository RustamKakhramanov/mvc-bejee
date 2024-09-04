<?php

namespace App\Repositories;

/**
 * @property Model $model
 */
abstract class Repository
{
    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    abstract protected function getModelClass(): string;
}
