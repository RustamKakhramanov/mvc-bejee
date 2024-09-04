<?php


namespace Core;

use \PDO;
use PDOException;

class Database
{
    public static function getDB()
    {
        $config = config('db');

        $host = $config['host'];
        $port = $config['port'];
        $db = $config['name'];
        $user = $config['user'];
        $password = $config['password'];

        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            // подключение к базе данных
            $pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            return $pdo;
        } catch (PDOException $e) {
            die($e->getMessage());
        } finally {
            if (isset($pdo)) {
                $pdo = null;
            }
        }
    }
}
