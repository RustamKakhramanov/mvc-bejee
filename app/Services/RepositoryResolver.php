<?php

namespace App\Services;

use App\Repositories\CountryRepository;
use App\Repositories\Repository;

class RepositoryResolver
{
    public function resolve(string $type): Repository
    {
        switch ($type) {
            default:
                return new CountryRepository();
                break;
        }
    }
}
