<?php

namespace App\Models;

use Core\Model;

class City extends Model
{
    protected string $table = 'city';

    protected array $attributes = ['name', 'region_id', 'country_id'];
}
