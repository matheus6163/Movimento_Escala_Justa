<?php
namespace App\Models;

class Comment
{
    public static function all(string $status = ''): array
    {
        $sql = 'SELECT cm.*, u.name AS user_name, u.email AS user_email, ct.title AS content_title
                  FROM comments cm
                  JOIN users u ON u.id = cm.user_id
                  JOIN contents ct ON ct.id = cm.content_id';
        $params = [];
        if (in_array($status, ['pending', 'approved'], true)) {
            $sql .= ' WHERE cm.status = ?';
            $params[] = $status;
        }
        $stmt = Database::connect()->prepare($sql . ' ORDER BY cm.created_at DESC');
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connect()->prepare('SELECT * FROM comments WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function approve(int $id): bool
    {
        return Database::connect()
            ->prepare("UPDATE comments SET status = 'approved' WHERE id = ?")
            ->execute([$id]);
    }

    public static function updateBody(int $id, string $body): bool
    {
        return Database::connect()
            ->prepare('UPDATE comments SET body = ? WHERE id = ?')
            ->execute([$body, $id]);
    }

    public static function delete(int $id): bool
    {
        return Database::connect()->prepare('DELETE FROM comments WHERE id = ?')->execute([$id]);
    }

    public static function count(string $status = ''): int
    {
        if ($status === '') {
            return (int) Database::connect()->query('SELECT COUNT(*) FROM comments')->fetchColumn();
        }
        $stmt = Database::connect()->prepare('SELECT COUNT(*) FROM comments WHERE status = ?');
        $stmt->execute([$status]);
        return (int) $stmt->fetchColumn();
    }
}
