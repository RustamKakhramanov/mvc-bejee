<?php


namespace App\Models;


use Core\Model;
use PDO;

class Task extends Model
{
    protected $table  = 'tasks';

    protected $attributes = [
        'name', 'email', 'text'
    ];


    public function getAll() {
        $attributes = implode(',', $this->attributes);
        $table = $this->table;

        $stmt = $this->DB->query("SELECT $attributes, name FROM $table");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setAttributes() {

    }

}