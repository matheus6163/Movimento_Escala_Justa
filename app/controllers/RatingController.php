<?php
namespace App\Controllers;

use App\Models\Content;
use App\Models\Rating;

/**
 *  POST /rating/store/{contentId}   campo "value" = 1 ou -1 (cria ou troca o voto)
 *  POST /rating/remove/{contentId}  (desfaz o voto)
 */
class RatingController extends Controller
{
    public function store(string $contentId = ''): void
    {
        $this->requirePost();
        require_login();                  // RF015: sem login vai para a tela de login
        $c = $this->publishedOr404($contentId);

        $value = (int) ($_POST['value'] ?? 0);
        if ($value !== 1 && $value !== -1) {
            http_response_code(422);
            exit('Avaliação inválida');
        }
        Rating::save((int) current_user()['id'], (int) $c['id'], $value);
        flash($value === 1 ? 'Você curtiu este conteúdo.' : 'Você descurtiu este conteúdo.');
        redirect('user/conteudo/' . (int) $c['id']);
    }

    public function remove(string $contentId = ''): void
    {
        $this->requirePost();
        require_login();
        $c = $this->publishedOr404($contentId);

        Rating::remove((int) current_user()['id'], (int) $c['id']);
        flash('Avaliação removida.');
        redirect('user/conteudo/' . (int) $c['id']);
    }

    private function publishedOr404(string $id): array
    {
        $c = Content::find((int) $id);
        if (!$c || $c['status'] !== 'published') {
            http_response_code(404);
            exit('Conteúdo não encontrado');
        }
        return $c;
    }
}
