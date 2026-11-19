<?php

namespace App\Models;

use PDO;
use PDOException;

class Comentario
{
    private static function conectar()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=meu_mvc;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function listarTodos(): array
    {
        $pdo = self::conectar();
        $stmt = $pdo->query("SELECT * FROM comentarios ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function alterarStatus($id, string $novoStatus): bool
    {
        $pdo = self::conectar();
        $stmt = $pdo->prepare("UPDATE comentarios SET status = :status WHERE id = :id");
        return $stmt->execute([
            ':status' => $novoStatus,
            ':id'     => (int) $id
        ]);
    }

    public static function editarTexto($id, string $novoTexto): bool
    {
        $pdo = self::conectar();
        $stmt = $pdo->prepare("UPDATE comentarios SET texto = :texto WHERE id = :id");
        return $stmt->execute([
            ':texto' => $novoTexto,
            ':id'    => (int) $id
        ]);
    }

    public static function excluir($id): bool
    {
        $pdo = self::conectar();
        $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id = :id");
        return $stmt->execute([':id' => (int) $id]);
    }
}
