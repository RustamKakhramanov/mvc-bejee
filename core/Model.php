<?php

namespace Core;

use Core\Collection;
use Core\Database;
use PDO;

abstract class Model
{
    protected PDO $DB;

    protected string $query = '';
    protected string $table = '';
    protected array $attributes = [];

    public function __construct()
    {
        $this->DB = Database::getDB();
    }

    protected function getInitialQuery(array $customAttr = []): string
    {
        $attributes = implode(',', $customAttr ?: $this->attributes);
        return "SELECT $attributes FROM $this->table ";
    }

    public function getAll(int $page = null, int $limit = null, string $orderBy = 'id'): array
    {
        $this->query = $this->getInitialQuery();
        $this->query .= " ORDER BY $orderBy DESC";
        $returned = [];

        if ($page) {
            $countQuery = $this->DB->query("SELECT count(*) FROM $this->table");
            $countQuery->execute();
            $totalCount = $countQuery->fetchColumn();
            $offset = ($page - 1) * $limit;
            $pages = ceil($totalCount / $limit);

            if ($page > $pages) {
                throw new \Exception('The requested page is greater than the number of pages');
            }

            $this->query .= " LIMIT $limit OFFSET $offset";
            $returned['pages'] = $pages;
        }

        $stmt = $this->DB->query($this->query);
        $stmt->execute();
        $returned['data'] = $this->toCollection($stmt->fetchAll())->get();

        return $returned;
    }

    public function create(array $values): int
    {
        $keys = implode(',', array_keys($values));
        $placeholders = implode(',', array_fill(0, count($values), '?'));

        $query = "INSERT INTO $this->table ($keys) VALUES ($placeholders)";
        $statement = $this->DB->prepare($query);
        $statement->execute(array_values($values));

        return (int)$this->DB->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $query = $this->getInitialQuery() . "WHERE id = ? LIMIT 1";
        $stmt = $this->DB->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    protected function toCollection(array $data): Collection
    {
        return new Collection($this->attributes, $data);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getByRaw(string $query): array
    {
        $stmt = $this->DB->query($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
