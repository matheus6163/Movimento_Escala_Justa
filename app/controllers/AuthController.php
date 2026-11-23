<?php
namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{
    public function login(): void
    {
        start_session();
        $error = null;
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            check_csrf();
            $email = trim($_POST['email'] ?? '');
            $pass  = (string) ($_POST['password'] ?? '');
            $user  = User::findByEmail($email);

            if ($user && $user['password_hash'] && password_verify($pass, $user['password_hash'])) {
                session_regenerate_id(true);   // evita fixação de sessão
                $_SESSION['user'] = [
                    'id'   => (int) $user['id'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                ];
                redirect($user['role'] === 'admin' ? 'admin/index' : 'user/conteudo');
            }
            http_response_code(401);
            $error = 'E-mail ou senha inválidos.';
        }

        $this->render('auth/login', [
            'title' => 'Entrar',
            'error' => $error,
            'email' => $email,
            'csrf'  => csrf_token(),
        ]);
    }

    public function logout(): void
    {
        $this->requirePost();
        $_SESSION = [];
        session_destroy();
        redirect('auth/login');
    }
}
