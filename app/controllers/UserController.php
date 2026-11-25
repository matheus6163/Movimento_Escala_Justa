<?php
namespace App\Controllers;

use App\Models\Comment;
use App\Models\Content;
use App\Models\Rating;
use App\Models\User;
use App\Validators\UserValidator;

/**
 * Acesso:
 *  - público ............ create, store            (RF001: criar conta)
 *  - logado ............. conteudo, perfil, perfilUpdate, senhaUpdate, perfilDestroy
 *  - só administrador ... index, show, edit, update, destroy (gestão de usuários)
 */
class UserController extends Controller
{
    // RF010 — /user/conteudo (lista) e /user/conteudo/ID (um conteúdo). Exige login.
    public function conteudo(string $id = ''): void
    {
        require_login();
        $user  = current_user();
        $admin = is_admin();

        if ($id === '') {
            $this->render('users/conteudo_lista', [
                'title'    => 'Conteúdos',
                'contents' => Content::listWithCounts(!$admin),   // admin vê também os rascunhos
                'isAdmin'  => $admin,
            ]);
            return;
        }

        $c = Content::find((int) $id);
        if (!$c || ($c['status'] !== 'published' && !$admin)) {
            http_response_code(404);
            exit('Conteúdo não encontrado');
        }

        $this->render('users/conteudo', [
            'title'       => $c['title'],
            'item'        => $c,          // não use "content": o layout já usa essa variável
            'isPublished' => $c['status'] === 'published',
            'isAdmin'     => $admin,
            'userId'      => (int) $user['id'],
            'comments'    => Comment::visibleFor((int) $c['id'], (int) $user['id']),
            'counts'      => Rating::countsFor((int) $c['id']),
            'myVote'      => Rating::valueOf((int) $user['id'], (int) $c['id']),
            'csrf'        => csrf_token(),
        ]);
    }

    // ===== RF001 — CREATE: criar conta (público) =====
    public function create(): void
    {
        $this->render('users/form', [
            'title'  => 'Novo usuário',
            'errors' => [],
            'old'    => [],
            'csrf'   => csrf_token(),
        ]);
    }

    public function store(): void
    {
        $this->requirePost();
        $input  = $this->userInput();
        $errors = $this->validateUser($input, null, true);   // senha obrigatória

        if ($errors) {
            http_response_code(422);
            $input['password'] = '';
            $this->render('users/form', [
                'title'  => 'Novo usuário',
                'errors' => $errors,
                'old'    => $input,
                'csrf'   => csrf_token(),
            ]);
            return;
        }

        User::create($input['name'], $input['last_name'], $input['email'], $input['password']);

        if (is_admin()) {
            flash('Usuário cadastrado.');
            redirect('user/index');
        }
        flash('Cadastro realizado! Faça login para continuar.');
        redirect('auth/login');
    }

    // ===== RF001 — READ / UPDATE / DELETE da PRÓPRIA conta =====
    public function perfil(): void
    {
        require_login();
        $user = $this->findOr404((string) current_user()['id']);
        $this->renderPerfil($user, $user);
    }

    public function perfilUpdate(): void
    {
        require_login();
        $this->requirePost();
        $user  = $this->findOr404((string) current_user()['id']);
        $input = $this->userInput();
        $input['password'] = '';                         // a senha tem formulário próprio
        $errors = $this->validateUser($input, (int) $user['id'], false);

        if ($errors) {
            http_response_code(422);
            $this->renderPerfil($user, $input, $errors);
            return;
        }
        User::update((int) $user['id'], $input);
        $_SESSION['user']['name'] = $input['name'];      // mantém o nome da sessão atualizado
        flash('Dados atualizados.');
        redirect('user/perfil');
    }

    public function senhaUpdate(): void
    {
        require_login();
        $this->requirePost();
        $id   = (int) current_user()['id'];
        $user = $this->findOr404((string) $id);

        $current = (string) ($_POST['current_password'] ?? '');
        $new     = (string) ($_POST['new_password'] ?? '');
        $confirm = (string) ($_POST['confirm_password'] ?? '');

        $errors = [];
        $hash = User::passwordHash($id);
        if (!$hash || !password_verify($current, $hash)) {
            $errors['current_password'] = 'Senha atual incorreta.';
        }
        if (mb_strlen($new) < 8 || mb_strlen($new) > 72) {
            $errors['new_password'] = 'A nova senha deve ter entre 8 e 72 caracteres.';
        } elseif ($new !== $confirm) {
            $errors['confirm_password'] = 'A confirmação não confere com a nova senha.';
        }

        if ($errors) {
            http_response_code(422);
            $this->renderPerfil($user, $user, [], $errors);
            return;
        }
        User::changePassword($id, $new);
        session_regenerate_id(true);
        flash('Senha alterada com sucesso.');
        redirect('user/perfil');
    }

    public function perfilDestroy(): void
    {
        require_login();
        $this->requirePost();
        $id = (int) current_user()['id'];

        if (is_admin()) {   // evita ficar sem administrador
            flash('A conta de administrador não pode ser excluída por aqui.');
            redirect('user/perfil');
        }
        $hash = User::passwordHash($id);
        if (!$hash || !password_verify((string) ($_POST['password'] ?? ''), $hash)) {
            flash('Senha incorreta. Sua conta não foi excluída.');
            redirect('user/perfil');
        }

        User::delete($id);                // comentários e avaliações saem por ON DELETE CASCADE
        $_SESSION = [];
        session_destroy();
        start_session();                  // nova sessão só para mostrar o aviso
        session_regenerate_id(true);
        flash('Sua conta foi excluída.');
        redirect('auth/login');
    }

    // ===== Gestão de usuários pelo administrador (extra, além dos RF) =====
    public function index(): void
    {
        require_admin();
        $this->render('users/index', [
            'title' => 'Usuários',
            'users' => User::all(),
            'csrf'  => csrf_token(),
        ]);
    }

    public function show(string $id = ''): void
    {
        require_admin();
        $user = $this->findOr404($id);
        $this->render('users/index', [
            'title' => 'Usuário',
            'users' => [$user],
            'csrf'  => csrf_token(),
        ]);
    }

    public function edit(string $id = ''): void
    {
        require_admin();
        $user = $this->findOr404($id);
        $this->render('users/edit', [
            'title'  => 'Editar usuário',
            'user'   => $user,
            'errors' => [],
            'old'    => $user,
            'csrf'   => csrf_token(),
        ]);
    }

    public function update(string $id = ''): void
    {
        require_admin();
        $this->requirePost();
        $user   = $this->findOr404($id);
        $input  = $this->userInput();
        $errors = $this->validateUser($input, (int) $user['id'], false);   // senha opcional

        if ($errors) {
            http_response_code(422);
            $input['password'] = '';
            $this->render('users/edit', [
                'title'  => 'Editar usuário',
                'user'   => $user,
                'errors' => $errors,
                'old'    => $input,
                'csrf'   => csrf_token(),
            ]);
            return;
        }
        User::update((int) $user['id'], $input);
        if ((int) $user['id'] === (int) current_user()['id']) {
            $_SESSION['user']['name'] = $input['name'];
        }
        flash('Usuário atualizado.');
        redirect('user/index');
    }

    public function destroy(string $id = ''): void
    {
        require_admin();
        $this->requirePost();
        $user = $this->findOr404($id);

        if ((int) $user['id'] === (int) current_user()['id']) {
            flash('Você não pode excluir a própria conta.');
            redirect('user/index');
        }
        User::delete((int) $user['id']);
        flash('Usuário excluído.');
        redirect('user/index');
    }

    // ===== Auxiliares (privados: não viram rota) =====
    private function renderPerfil(array $user, array $old, array $errors = [], array $pwErrors = []): void
    {
        $this->render('users/perfil', [
            'title'    => 'Minha conta',
            'user'     => $user,
            'old'      => $old,
            'errors'   => $errors,
            'pwErrors' => $pwErrors,
            'isAdmin'  => is_admin(),
            'csrf'     => csrf_token(),
        ]);
    }

    private function userInput(): array
    {
        return [
            'name'      => trim($_POST['name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email'     => trim($_POST['email'] ?? ''),
            'password'  => (string) ($_POST['password'] ?? ''),
        ];
    }

    private function validateUser(array $input, ?int $ignoreId = null, bool $requirePassword = false): array
    {
        $errors = (new UserValidator())->validate($input, $requirePassword);
        if (!isset($errors['email']) && User::emailExists($input['email'], $ignoreId)) {
            $errors['email'] = 'Este e-mail já está cadastrado.';
        }
        return $errors;
    }

    private function findOr404(string $id): array
    {
        $user = User::find((int) $id);
        if (!$user) {
            http_response_code(404);
            exit('Usuário não encontrado');
        }
        return $user;
    }
}

