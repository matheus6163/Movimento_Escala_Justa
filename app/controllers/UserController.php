<?php
namespace App\Controllers;

use App\Models\User;
use App\Validators\UserValidator;

class UserController
{
    public function index(): void
    {
	
        $this->render('users/index', [
            'title' => 'Usuários',
            'users' => User::all(),
        ]);
    }
    public function show(string $id = ''): void
    {
        $user =  User::find((int) $id);
        if (!$user) {
            http_response_code(404);
            exit('Usuário não encontrado');
        }
        $this->render('users/index', [
            'title' => 'Usuário',
            'users' => [$user],
        ]);
    }

    public function create(): void
    {
        $this->render('users/form', [
            'title'  => 'Novo usuário',
            'errors' => [],
            'old'    => [],
            'csrf'   => $this->csrfToken(),
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido');
        }
        $this->checkCsrf();

        $input = [
            'name'  => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ];

        $errors = (new UserValidator())->validate($input);
        if (!$errors &&  User::emailExists($input['email'])) {
            $errors['email'] = 'Este e-mail já está cadastrado.';
        }

        if ($errors) {
            http_response_code(422);
            $this->render('users/form', [
                'title'  => 'Novo usuário',
                'errors' => $errors,
                'old'    => $input,
                'csrf'   => $this->csrfToken(),
            ]);
            return;
        }

        User::create($input['name'], $input['email']);
        header('Location: ' . url('user/index'));
        exit;
    }

    private function csrfToken(): string
    {
        session_status() === PHP_SESSION_NONE && session_start();
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    private function checkCsrf(): void
    {
        session_status() === PHP_SESSION_NONE && session_start();
        if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(403);
            exit('Token inválido');
        }
    }

    private function render(string $view, array $data = []): void
    {
        $base = __DIR__ . '/../Views/';

        $renderFile = static function (string $__file, array $__data): string {
            extract($__data, EXTR_SKIP);
            ob_start();
            require $__file;
            return ob_get_clean();
        };

        $content = $renderFile($base . $view . '.php', $data);
        $vars = $data + ['content' => $content];
        echo $renderFile($base . 'layouts/main.php', $vars);
    }
}