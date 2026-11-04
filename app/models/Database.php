<?php
namespace App\Models;
use PDO;
class Database
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo !== null)
            return self::$pdo;

        $db = require __DIR__ . '/../../config/database.php';
        $dsn = "mysql:host={$db['host']};"
            . "dbname={$db['name']};charset={$db['charset']}";

        self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        
        return self::$pdo;
    }
}
