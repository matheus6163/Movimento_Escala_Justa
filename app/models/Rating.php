<?php
namespace App\Models;

class Rating
{
    /** Curtidas e descurtidas por conteúdo (RF014). */
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

    /** Quem avaliou o quê (relatório detalhado, só para o administrador). */
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
