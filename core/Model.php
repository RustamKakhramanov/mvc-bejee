<?php

namespace Core;

use PDO;

abstract class Model
{
    protected PDO $DB;

    const PAGES_LIMIT = 3;

    protected string $query = '';
    protected string $table = '';
    protected $attributes = [];

    public function __construct() {
        $this->DB = Database::getDB();
    }

    protected function getInitialQuery($custom_attr = []): string
    {
        $attributes = implode(',', $custom_attr ? : $this->attributes);
        $table = $this->table;

        return "SELECT $attributes, name FROM $table ";
    }

    public function getAll($page = null, $limit = null, $orderBy = 'id') {
        $returned = [];

        $this->query  =  $this->getInitialQuery();
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

        $returned['data'] =  $this->toCollection($stmt->fetchAll())->get();

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

    public function createAndGet($values) {
        $id = $this->write($values);
        $query = $this->getInitialQuery();
        $query .= "WHERE id=? LIMIT 1";

        $stmt = $this->DB->prepare($query);
        $stmt->execute([$id]);

        return $this->toCollection($stmt->fetchAll())->first();
    }

    protected function toCollection($data) {
        return (new Collection($this->attributes, $data));
    }

    public function getWithPaginateAndSorting($page, $limit, $sorting) {
        return $this->getAll($page, $limit, $sorting);
    }

    public function get() {
        return json_decode(json_encode($this->DB->query($this->query)->fetchAll(PDO::FETCH_ASSOC)));
    }

}
