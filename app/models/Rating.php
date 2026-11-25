<?php
namespace App\Models;

class Rating
{
    /** CREATE/UPDATE (RF012/RF013): a UNIQUE (user_id, content_id) permite "upsert". */
    public static function save(int $userId, int $contentId, int $value): bool
    {
        return Database::connect()->prepare(
            'INSERT INTO ratings (user_id, content_id, value) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE value = VALUES(value)'
        )->execute([$userId, $contentId, $value]);
    }

    /** READ: voto atual do usuário (1, -1) ou null se ainda não votou. */
    public static function valueOf(int $userId, int $contentId): ?int
    {
        $stmt = Database::connect()->prepare(
            'SELECT value FROM ratings WHERE user_id = ? AND content_id = ?'
        );
        $stmt->execute([$userId, $contentId]);
        $v = $stmt->fetchColumn();
        return $v === false ? null : (int) $v;
    }

    /** READ (RF014): curtidas e descurtidas de um conteúdo. */
    public static function countsFor(int $contentId): array
    {
        $stmt = Database::connect()->prepare(
            'SELECT COALESCE(SUM(value = 1), 0) AS likes,
                    COALESCE(SUM(value = -1), 0) AS dislikes
               FROM ratings WHERE content_id = ?'
        );
        $stmt->execute([$contentId]);
        return $stmt->fetch();
    }

    /** DELETE (RF016): o usuário desfaz o próprio voto. */
    public static function remove(int $userId, int $contentId): bool
    {
        return Database::connect()
            ->prepare('DELETE FROM ratings WHERE user_id = ? AND content_id = ?')
            ->execute([$userId, $contentId]);
    }

    /** Totais por conteúdo (relatório do admin). */
    public static function totals(): array
    {
        return Database::connect()->query(
            'SELECT ct.id, ct.title,
                    COALESCE(SUM(r.value = 1), 0)  AS likes,
                    COALESCE(SUM(r.value = -1), 0) AS dislikes
               FROM contents ct
          LEFT JOIN ratings r ON r.content_id = ct.id
           GROUP BY ct.id, ct.title
           ORDER BY ct.created_at DESC'
        )->fetchAll();
    }

    /** Quem avaliou o quê (relatório detalhado, só admin). */
    public static function details(): array
    {
        return Database::connect()->query(
            'SELECT r.value, r.created_at, u.name AS user_name, u.email AS user_email,
                    ct.title AS content_title
               FROM ratings r
               JOIN users u ON u.id = r.user_id
               JOIN contents ct ON ct.id = r.content_id
           ORDER BY r.created_at DESC'
        )->fetchAll();
    }
}
