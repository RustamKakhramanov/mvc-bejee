<?php

namespace Core;

use PDO;
use App\Config;

abstract class Model
{
    protected PDO $DB;

    public function __construct() {
        $this->DB = Database::getDB();
    }


    protected function write($params) {

    }
}
