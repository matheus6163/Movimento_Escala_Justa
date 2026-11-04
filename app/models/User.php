<?php
namespace App\Models;

// Database pertence ao mesmo namespace App\Models.

class User 
{
    public static function all(): array
    {
        return Database::connect()
            ->query('SELECT * FROM users ORDER BY name')
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connect()->prepare(
            'SELECT * FROM users WHERE id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function emailExists(string $email): bool
    {
        $stmt = Database::connect()->prepare('SELECT 1 FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return (bool) $stmt->fetchColumn();
    }

    public static function create(string $name, string $email): int
    {
        $stmt = Database::connect()->prepare(
            'INSERT INTO users (name, email) VALUES (?, ?)'
        );
        $stmt->execute([$name, $email]);
        return (int) Database::connect()->lastInsertId();
    }
}
