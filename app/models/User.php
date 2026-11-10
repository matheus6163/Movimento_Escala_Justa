<?php
namespace App\Models;

// Database pertence ao mesmo namespace App\Models.

class User 
{
    public static function all(): array
    {
        return Database::connect()
            ->query('SELECT id, name, last_name, email, cpf, birth_date FROM users ORDER BY name')
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connect()->prepare(
            'SELECT id, name, last_name, email, cpf, birth_date FROM users WHERE id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function emailExists(string $email, ?int $ignoreId = null): bool
    {
        $sql = 'SELECT 1 FROM users WHERE email = ?';
        $params = [$email];
        if ($ignoreId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }
        $stmt = Database::connect()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetchColumn();
    }

    public static function cpfExists(string $cpf, ?int $ignoreId = null): bool
    {
        $sql = 'SELECT 1 FROM users WHERE cpf = ?';
        $params = [$cpf];
        if ($ignoreId !== null) { {
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }
        $stmt = Database::connect()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetchColumn();
    }
    }
    public static function create($name, $lastName, $email, $cpf, $birth): int{
        $stmt = Database::connect()->prepare(
            'INSERT INTO users (name, last_name, email, cpf, birth_date) VALUES (?, ?, ?, ?,?)'
        );
        $stmt->execute([$name, $lastName, $email, $cpf, $birth]);
        return (int) Database::connect()->lastInsertId();
    }
    public static function update(int $id, array $data): bool 
    {
        $stmt = Database::connect()->prepare(
        'UPDATE users
            SET name = ?, last_name = ?, email = ?, cpf = ?, birth_date = ?
        WHERE id = ?'
    );
        return $stmt->execute([
            $data['name'], $data['last_name'], $data['email'],
            $data['cpf'], $data['birth_date'],
            $id,
        ]);
    }
}
