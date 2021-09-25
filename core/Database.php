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
            $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
            // make a database connection
            $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            return $pdo;

        } catch (PDOException $e) {
            die($e->getMessage());
        } finally {
            if ($pdo) {
                $pdo = null;
            }
        }

    }
}