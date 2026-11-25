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

    /** CREATE (RF002): nasce 'pending' (default da tabela) até o admin aprovar. */
    public static function create(int $userId, int $contentId, string $body): int
    {
        $db = Database::connect();
        $db->prepare('INSERT INTO comments (user_id, content_id, body) VALUES (?, ?, ?)')
           ->execute([$userId, $contentId, $body]);
        return (int) $db->lastInsertId();
    }

    /** READ (RF003): aprovados de todos + os PRÓPRIOS ainda pendentes. */
    public static function visibleFor(int $contentId, int $userId): array
    {
        $stmt = Database::connect()->prepare(
            "SELECT cm.*, u.name AS user_name
               FROM comments cm
               JOIN users u ON u.id = cm.user_id
              WHERE cm.content_id = ? AND (cm.status = 'approved' OR cm.user_id = ?)
              ORDER BY cm.created_at DESC"
        );
        $stmt->execute([$contentId, $userId]);
        return $stmt->fetchAll();
    }

    /**
     * UPDATE pelo autor (RF004). O "AND user_id = ?" impede editar comentário
     * alheio. Volta para 'pending' para o admin reaprovar.
     */
    public static function updateByAuthor(int $id, int $userId, string $body): bool
    {
        return Database::connect()->prepare(
            "UPDATE comments SET body = ?, status = 'pending' WHERE id = ? AND user_id = ?"
        )->execute([$body, $id, $userId]);
    }

    /** DELETE pelo autor (RF005). */
    public static function deleteByAuthor(int $id, int $userId): bool
    {
        return Database::connect()
            ->prepare('DELETE FROM comments WHERE id = ? AND user_id = ?')
            ->execute([$id, $userId]);
    }

    // ---- usados pelo painel do administrador (moderação, RF011) ----
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

