<?php


namespace App\Models;


use Core\Model;
use PDO;

class Task extends Model
{
    protected string $table  = 'tasks';

    protected $attributes = [
        'name', 'email', 'text'
    ];

}