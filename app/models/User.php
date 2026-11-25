<?php
namespace App\Models;

class User
{
    public static function all(): array
    {
        return Database::connect()
            ->query('SELECT id, name, last_name, email, role FROM users ORDER BY name')
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connect()->prepare(
            'SELECT id, name, last_name, email, role FROM users WHERE id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connect()->prepare(
            'SELECT id, name, email, password_hash, role FROM users WHERE email = ?'
        );
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    /** Hash atual da senha (para conferir a "senha atual" antes de trocar/excluir). */
    public static function passwordHash(int $id): ?string
    {
        $stmt = Database::connect()->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $h = $stmt->fetchColumn();
        return $h === false || $h === null ? null : (string) $h;
    }

    /** RF001: alterar a própria senha. */
    public static function changePassword(int $id, string $newPassword): bool
    {
        return Database::connect()
            ->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
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

    /** CREATE: grava a senha (hash). O perfil nasce sempre como 'user'. */
    public static function create(string $name, string $lastName, string $email, string $password): int
    {
        $db = Database::connect();
        $db->prepare(
            "INSERT INTO users (name, last_name, email, password_hash, role)
             VALUES (?, ?, ?, ?, 'user')"
        )->execute([$name, $lastName, $email, password_hash($password, PASSWORD_DEFAULT)]);
        return (int) $db->lastInsertId();
    }

    /** UPDATE: só troca a senha se $data['password'] vier preenchida. */
    public static function update(int $id, array $data): bool
    {
        $password = (string) ($data['password'] ?? '');
        if ($password !== '') {
            return Database::connect()->prepare(
                'UPDATE users SET name = ?, last_name = ?, email = ?, password_hash = ? WHERE id = ?'
            )->execute([
                $data['name'], $data['last_name'], $data['email'],
                password_hash($password, PASSWORD_DEFAULT), $id,
            ]);
        }
        return Database::connect()->prepare(
            'UPDATE users SET name = ?, last_name = ?, email = ? WHERE id = ?'
        )->execute([$data['name'], $data['last_name'], $data['email'], $id]);
    }

    /** DELETE. Comentários e avaliações somem junto (ON DELETE CASCADE). */
    public static function delete(int $id): bool
    {
        return Database::connect()
            ->prepare('DELETE FROM users WHERE id = ?')
            ->execute([$id]);
 
    }
 }