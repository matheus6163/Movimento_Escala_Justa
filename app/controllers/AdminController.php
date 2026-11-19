<?php

namespace App\Controllers;

use App\Models\Conteudo;
use App\Models\Comentario;

class AdminController
{
    public function index(): void
    {
        $conteudos = Conteudo::listarTodos();
        require_once dirname(__DIR__) . '/views/admin/dashboard.php';
    }

    public function home(): void
    {
        require_once dirname(__DIR__) . '/views/admin/home_admin.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'] ?? '';
            $legenda = $_POST['legenda'] ?? '';
            $status = $_POST['status'] ?? 'publicado';
            $idAdmin = $_SESSION['id_usuario'] ?? 1;

            $nomeImagem = '';
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $nomeImagem = uniqid() . '.' . $extensao;
                move_uploaded_file($_FILES['imagem']['tmp_name'], dirname(__DIR__, 2) . '/public/img/' . $nomeImagem);
            }

            $conteudo = new Conteudo();
            $conteudo->setTipoUsuario((int)$idAdmin);
            $conteudo->setTitulo($titulo);
            $conteudo->setLegenda($legenda);
            $conteudo->setImagem($nomeImagem);
            $conteudo->setStatus($status);

            if ($conteudo->salvar()) {
                header('Location: ' . url('admin'));
                exit;
            }
        }

        require_once dirname(__DIR__) . '/views/admin/criar_conteudo.php';
    }

    public function edit($id): void
    {
        $conteudo = Conteudo::buscarPorId($id);

        if (!$conteudo) {
            header('Location: ' . url('admin'));
            exit;
        }

        require_once dirname(__DIR__) . '/views/admin/editar_conteudo.php';
    }

    public function update($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo  = trim($_POST['titulo'] ?? '');
            $legenda = trim($_POST['legenda'] ?? '');
            $status  = $_POST['status'] ?? 'publicado';

            $novaImagem = null;
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $extensao   = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $novaImagem = uniqid() . '.' . $extensao;
                move_uploaded_file($_FILES['imagem']['tmp_name'], dirname(__DIR__, 2) . '/public/img/' . $novaImagem);
            }

            Conteudo::atualizar($id, $titulo, $legenda, $status, $novaImagem);
        }

        header('Location: ' . url('admin'));
        exit;
    }

    public function delete($id): void
    {
        Conteudo::excluir($id);
        header('Location: ' . url('admin'));
        exit;
    }

    public function comentarios(): void
    {
        $comentarios = Comentario::listarTodos();
        require_once dirname(__DIR__) . '/views/admin/comentarios.php';
    }

    public function statusComentario($id, string $acao): void
    {
        $status = ($acao === 'aprovar') ? 'aprovado' : 'oculto';
        Comentario::alterarStatus($id, $status);

        header('Location: ' . url('admin/comentarios'));
        exit;
    }

    public function editarComentario(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id    = (int) ($_POST['id'] ?? 0);
            $texto = trim($_POST['texto'] ?? '');

            if ($id > 0 && $texto !== '') {
                Comentario::editarTexto($id, $texto);
            }
        }

        header('Location: ' . url('admin/comentarios'));
        exit;
    }

    public function excluirComentario($id): void
    {
        Comentario::excluir($id);
        header('Location: ' . url('admin/comentarios'));
        exit;
    }
}