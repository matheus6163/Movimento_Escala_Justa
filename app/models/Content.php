<?php
namespace App\Models;

class Content
{
    public static function all(): array
    {
        return Database::connect()
            ->query('SELECT * FROM contents ORDER BY created_at DESC')
            ->fetchAll();
    }

    /**
     * READ (RF010 + RF014): lista com contador de curtidas/descurtidas.
     * $onlyPublished = true  -> usuário comum (só publicados)
     * $onlyPublished = false -> administrador (todos, inclusive rascunhos)
     */
    public static function listWithCounts(bool $onlyPublished): array
    {
        $sql = 'SELECT ct.*,
                       COALESCE(SUM(r.value = 1), 0)  AS likes,
                       COALESCE(SUM(r.value = -1), 0) AS dislikes
                  FROM contents ct
             LEFT JOIN ratings r ON r.content_id = ct.id'
             . ($onlyPublished ? " WHERE ct.status = 'published'" : '')
             . ' GROUP BY ct.id ORDER BY ct.created_at DESC';
        return Database::connect()->query($sql)->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connect()->prepare('SELECT * FROM contents WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $d): int
    {
        $stmt = Database::connect()->prepare(
            'INSERT INTO contents (title, caption, body, image, status) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$d['title'], $d['caption'], $d['body'], $d['image'], $d['status']]);
        return (int) Database::connect()->lastInsertId();
    }

    public static function update(int $id, array $d): bool
    {
        $stmt = Database::connect()->prepare(
            'UPDATE contents SET title = ?, caption = ?, body = ?, image = ?, status = ? WHERE id = ?'
        );
        return $stmt->execute([$d['title'], $d['caption'], $d['body'], $d['image'], $d['status'], $id]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connect()->prepare('DELETE FROM contents WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function count(): int
    {
        return (int) Database::connect()->query('SELECT COUNT(*) FROM contents')->fetchColumn();
    }
}

