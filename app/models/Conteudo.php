<?php

namespace App\Models;

use PDO;
use PDOException;

class Conteudo
{
    private $id;
    private $tipo_usuario;
    private $titulo;
    private $legenda;
    private $imagem;
    private $status;
    private $criado_em;

    private static function conectar()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=meu_mvc;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public function setTipoUsuario(int $tipo): void
    {
        $this->tipo_usuario = $tipo;
    }
    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }
    public function setLegenda(string $legenda): void
    {
        $this->legenda = $legenda;
    }
    public function setImagem(string $imagem): void
    {
        $this->imagem = $imagem;
    }
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function salvar(): bool
    {
        $pdo = self::conectar();
        $stmt = $pdo->prepare("INSERT INTO conteudos (tipo_usuario, titulo, legenda, imagem, status, criado_em) VALUES (:tipo, :titulo, :legenda, :imagem, :status, NOW())");
        return $stmt->execute([
            ':tipo'    => $this->tipo_usuario,
            ':titulo'  => $this->titulo,
            ':legenda' => $this->legenda,
            ':imagem'  => $this->imagem,
            ':status'  => $this->status
        ]);
    }

    public static function listarTodos(): array
    {
        $pdo = self::conectar();
        $stmt = $pdo->query("SELECT * FROM conteudos ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarPorId($id): ?array
    {
        $pdo = self::conectar();
        $stmt = $pdo->prepare("SELECT * FROM conteudos WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => (int) $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    public static function atualizar($id, string $titulo, string $legenda, string $status, ?string $novaImagem = null): bool
    {
        $pdo = self::conectar();

        if ($novaImagem) {
            $sql = "UPDATE conteudos SET titulo = :titulo, legenda = :legenda, status = :status, imagem = :imagem WHERE id = :id";
            $params = [
                ':titulo'  => $titulo,
                ':legenda' => $legenda,
                ':status'  => $status,
                ':imagem'  => $novaImagem,
                ':id'      => (int) $id
            ];
        } else {
            $sql = "UPDATE conteudos SET titulo = :titulo, legenda = :legenda, status = :status WHERE id = :id";
            $params = [
                ':titulo'  => $titulo,
                ':legenda' => $legenda,
                ':status'  => $status,
                ':id'      => (int) $id
            ];
        }

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public static function excluir($id): bool
    {
        $pdo = self::conectar();
        $stmt = $pdo->prepare("DELETE FROM conteudos WHERE id = :id");
        return $stmt->execute([':id' => (int) $id]);
    }
}
