<?php


namespace App\Models;


use Core\Model;

class Task extends Model
{
    protected string $table  = 'tasks';

    protected $attributes = [
        'name', 'email', 'text'
    ];

}