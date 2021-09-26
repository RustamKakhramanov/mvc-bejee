<?php

namespace Core;

use PDO;

abstract class Model
{
    protected PDO $DB;

    const PAGES_LIMIT = 3;

    protected string $query = '';
    protected string $table = '';

    public function __construct() {
        $this->DB = Database::getDB();
    }

    public function getAll($page = null, $limit = null, $orderBy = 'id') {
        $returned = [];

        $attributes = implode(',', $this->attributes);
        $table = $this->table;
        $this->query  =  "SELECT $attributes, name FROM $table";
        $this->query .= " ORDER BY $orderBy DESC";

        if ( $page  ) {
            $count_sl = $this->DB->query("SELECT count(*) FROM $this->table");
            $count_sl->execute();
            $limit = $limit ? : self::PAGES_LIMIT;
            $offset = $page - 1;
            $this->query .= " LIMIT " . $limit . " OFFSET " . $offset;
            $pages = (int) ceil($count_sl->fetch()[0] / $limit);

            if ($pages < $page) {
                throw new \Exception('The requested page is greater than the number of pages');
            }

            $returned['pages'] = $pages;
        }

        $stmt = $this->DB->query($this->query);
        $stmt->execute();

        $returned['data'] = json_decode(json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)));

        return $returned;
    }


    public function write($values = []) {
        $keys = implode(',', array_keys($values));
        $attr_count = '?';
        $attr_count .= str_repeat(',?', count($values) - 1);

        $this->query = "INSERT INTO $this->table ($keys) VALUES ($attr_count)";

        $statement = $this->DB->prepare($this->query);

        $statement->execute(array_values($values));

        $statement->fetchAll();

        return $this->DB->lastInsertId();
    }

    public function getWithPaginateAndSorting($page, $limit, $sorting) {
        return $this->getAll($page, $limit, $sorting);
    }

    public function get() {
        return json_decode(json_encode($this->DB->query($this->query)->fetchAll(PDO::FETCH_ASSOC)));
    }

}
