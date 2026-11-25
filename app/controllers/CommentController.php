<?php
namespace App\Controllers;

use App\Models\Comment;
use App\Models\Content;

/**
 *  POST /comment/store/{contentId}   RF002 — publicar (fica pendente até aprovar)
 *  GET  /comment/edit/{id}           RF004 — formulário de edição (só o autor)
 *  POST /comment/update/{id}         RF004 — salvar edição (volta para "pendente")
 *  POST /comment/destroy/{id}        RF005 — excluir o próprio comentário
 */
class CommentController extends Controller
{
    private const MAX = 1000;

    public function store(string $contentId = ''): void
    {
        $this->requirePost();
        require_login();
        $user = current_user();

        $c = Content::find((int) $contentId);
        if (!$c || $c['status'] !== 'published') {
            http_response_code(404);
            exit('Conteúdo não encontrado');
        }

        $body = trim($_POST['body'] ?? '');
        if ($body === '' || mb_strlen($body) > self::MAX) {
            flash('O comentário deve ter entre 1 e 1000 caracteres.');
        } else {
            Comment::create((int) $user['id'], (int) $c['id'], $body);
            flash('Comentário enviado! Ele ficará visível para todos depois de aprovado.');
        }
        redirect('user/conteudo/' . (int) $c['id']);
    }

    public function edit(string $id = ''): void
    {
        require_login();
        $cm = $this->ownedOr404($id);
        $this->render('comment/edit', [
            'title'   => 'Editar comentário',
            'comment' => $cm,
            'error'   => null,
            'csrf'    => csrf_token(),
        ]);
    }

    public function update(string $id = ''): void
    {
        $this->requirePost();
        require_login();
        $cm   = $this->ownedOr404($id);
        $body = trim($_POST['body'] ?? '');

        if ($body === '' || mb_strlen($body) > self::MAX) {
            http_response_code(422);
            $this->render('comment/edit', [
                'title'   => 'Editar comentário',
                'comment' => ['body' => $body] + $cm,
                'error'   => 'O comentário deve ter entre 1 e 1000 caracteres.',
                'csrf'    => csrf_token(),
            ]);
            return;
        }
        Comment::updateByAuthor((int) $cm['id'], (int) current_user()['id'], $body);
        flash('Comentário atualizado. Ele volta a ficar visível após nova aprovação.');
        redirect('user/conteudo/' . (int) $cm['content_id']);
    }

    public function destroy(string $id = ''): void
    {
        $this->requirePost();
        require_login();
        $cm = $this->ownedOr404($id);

        Comment::deleteByAuthor((int) $cm['id'], (int) current_user()['id']);
        flash('Comentário excluído.');
        redirect('user/conteudo/' . (int) $cm['content_id']);
    }

    /** Busca o comentário e garante que é do usuário logado (senão 404). */
    private function ownedOr404(string $id): array
    {
        $cm = Comment::find((int) $id);
        if (!$cm || (int) $cm['user_id'] !== (int) current_user()['id']) {
            http_response_code(404);
            exit('Comentário não encontrado');
        }
        return $cm;
    }
}

