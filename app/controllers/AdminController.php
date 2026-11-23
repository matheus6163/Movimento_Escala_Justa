<?php
<<<<<<< HEAD

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
=======
namespace App\Controllers;

use App\Models\Content;
use App\Models\Comment;
use App\Models\Rating;
use App\Validators\ContentValidator;

class AdminController extends Controller
{
    private const MAX_IMAGE = 2 * 1024 * 1024;           // 2 MB
    private const IMAGE_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    /** Guarda de acesso: roda em TODA requisição para /admin/... */
    public function __construct()
    {
        if (current_user() === null) {
            flash('Faça login para continuar.');
            redirect('auth/login');
        }
        if (!is_admin()) {
            http_response_code(403);
            exit('Acesso restrito ao administrador.');
        }
    }

    // ---------- Painel ----------
    public function index(): void
    {
        $this->render('admin/dashboard', [
            'title'    => 'Painel do administrador',
            'contents' => Content::count(),
            'pending'  => Comment::count('pending'),
            'comments' => Comment::count(),
        ]);
    }

    // ---------- Conteúdos (RF007, RF008, RF009, RF010) ----------
    public function contents(): void
    {
        $this->render('admin/contents', [
            'title'    => 'Conteúdos',
            'contents' => Content::all(),
            'csrf'     => csrf_token(),
        ]);
    }

    public function contentCreate(): void
    {
        $this->contentForm('Novo conteúdo', 'admin/contentStore', [
            'status' => 'draft',
        ]);
    }

    public function contentStore(): void
    {
        $this->requirePost();
        $input  = $this->contentInput();
        $errors = (new ContentValidator())->validate($input);
        $input['image'] = null;

        if (!$errors) {
            [$input['image'], $imgError] = $this->saveImage($_FILES['image'] ?? null);
            if ($imgError) { $errors['image'] = $imgError; }
        }
        if ($errors) {
            http_response_code(422);
            $this->contentForm('Novo conteúdo', 'admin/contentStore', $input, $errors);
            return;
        }
        Content::create($input);
        flash('Conteúdo criado.');
        redirect('admin/contents');
    }

    public function contentEdit(string $id = ''): void
    {
        $c = $this->findContentOr404($id);
        $this->contentForm('Editar conteúdo', 'admin/contentUpdate/' . $c['id'], $c);
    }

    public function contentUpdate(string $id = ''): void
    {
        $this->requirePost();
        $c      = $this->findContentOr404($id);
        $input  = $this->contentInput();
        $errors = (new ContentValidator())->validate($input);
        $input['image'] = $c['image'];
        $newImage = null;

        if (!$errors) {
            [$newImage, $imgError] = $this->saveImage($_FILES['image'] ?? null);
            if ($imgError) { $errors['image'] = $imgError; }
        }
        if ($errors) {
            http_response_code(422);
            $this->contentForm('Editar conteúdo', 'admin/contentUpdate/' . $c['id'],
                $input + ['id' => $c['id']], $errors);
            return;
        }
        if ($newImage) {                       // trocou a imagem: apaga a antiga
            $this->deleteImage($c['image']);
            $input['image'] = $newImage;
        }
        Content::update((int) $c['id'], $input);
        flash('Conteúdo atualizado.');
        redirect('admin/contents');
    }

    public function contentDelete(string $id = ''): void
    {
        $this->requirePost();
        $c = $this->findContentOr404($id);
        $this->deleteImage($c['image']);
        Content::delete((int) $c['id']);
        flash('Conteúdo excluído.');
        redirect('admin/contents');
    }

    // ---------- Comentários (RF011) ----------
    public function comments(): void
    {
        $status = $_GET['status'] ?? '';
        $this->render('admin/comments', [
            'title'    => 'Moderação de comentários',
            'comments' => Comment::all($status),
            'status'   => $status,
            'csrf'     => csrf_token(),
        ]);
    }

    public function commentApprove(string $id = ''): void
    {
        $this->requirePost();
        $cm = $this->findCommentOr404($id);
        Comment::approve((int) $cm['id']);
        flash('Comentário aprovado.');
        redirect('admin/comments');
    }

    public function commentEdit(string $id = ''): void
    {
        $cm = $this->findCommentOr404($id);
        $this->render('admin/comment_edit', [
            'title'   => 'Editar comentário',
            'comment' => $cm,
            'error'   => null,
            'csrf'    => csrf_token(),
        ]);
    }

    public function commentUpdate(string $id = ''): void
    {
        $this->requirePost();
        $cm   = $this->findCommentOr404($id);
        $body = trim($_POST['body'] ?? '');

        if ($body === '' || mb_strlen($body) > 1000) {
            http_response_code(422);
            $this->render('admin/comment_edit', [
                'title'   => 'Editar comentário',
                'comment' => ['body' => $body] + $cm,
                'error'   => 'O comentário deve ter entre 1 e 1000 caracteres.',
                'csrf'    => csrf_token(),
            ]);
            return;
        }
        Comment::updateBody((int) $cm['id'], $body);
        flash('Comentário atualizado.');
        redirect('admin/comments');
    }

    public function commentDelete(string $id = ''): void
    {
        $this->requirePost();
        $cm = $this->findCommentOr404($id);
        Comment::delete((int) $cm['id']);
        flash('Comentário removido.');
        redirect('admin/comments');
    }

    // ---------- Avaliações (RF014) ----------
    public function ratings(): void
    {
        $this->render('admin/ratings', [
            'title'   => 'Relatório de avaliações',
            'totals'  => Rating::totals(),
            'details' => Rating::details(),
        ]);
    }

    // ---------- Auxiliares (privados) ----------
    private function contentForm(string $title, string $action, array $old, array $errors = []): void
    {
        $this->render('admin/content_form', [
            'title'  => $title,
            'action' => $action,
            'old'    => $old,
            'errors' => $errors,
            'csrf'   => csrf_token(),
        ]);
    }

    private function contentInput(): array
    {
        return [
            'title'   => trim($_POST['title'] ?? ''),
            'caption' => trim($_POST['caption'] ?? ''),
            'body'    => trim($_POST['body'] ?? '') ?: null,
            'status'  => $_POST['status'] ?? '',
        ];
    }

    private function findContentOr404(string $id): array
    {
        $c = Content::find((int) $id);
        if (!$c) { http_response_code(404); exit('Conteúdo não encontrado'); }
        return $c;
    }

    private function findCommentOr404(string $id): array
    {
        $cm = Comment::find((int) $id);
        if (!$cm) { http_response_code(404); exit('Comentário não encontrado'); }
        return $cm;
    }

    /** @return array{0:?string,1:?string} [nome do arquivo salvo, mensagem de erro] */
    private function saveImage(?array $file): array
    {
        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) { return [null, null]; }
        if ($file['error'] !== UPLOAD_ERR_OK)  { return [null, 'Falha no envio da imagem.']; }
        if ($file['size'] > self::MAX_IMAGE)   { return [null, 'A imagem deve ter no máximo 2 MB.']; }

        // Confere o tipo real do arquivo (não confia na extensão enviada).
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!isset(self::IMAGE_TYPES[$mime])) {
            return [null, 'Envie uma imagem JPG, PNG ou WEBP.'];
        }
        $name = bin2hex(random_bytes(16)) . '.' . self::IMAGE_TYPES[$mime];
        if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../../public/uploads/' . $name)) {
            return [null, 'Não foi possível salvar a imagem.'];
        }
        return [$name, null];
    }

    private function deleteImage(?string $name): void
    {
        if ($name && preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $name)) {
            @unlink(__DIR__ . '/../../public/uploads/' . $name);
        }
    }
}
>>>>>>> correção
