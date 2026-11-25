<?php
// e(): escapa texto para HTML (previne XSS)
function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// url(): monta um link interno respeitando o BASE_URL
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

// asset(): monta o link de um arquivo estático e acrescenta a versão
function asset(string $path): string
{
    $file    = __DIR__ . '/../public/' . ltrim($path, '/');
    $version = is_file($file) ? filemtime($file) : false;
    return url($path) . ($version === false ? '' : '?v=' . $version);
}
function format_cpf(?string $cpf): string
{
    $cpf = (string) $cpf;
    return strlen($cpf) === 11
        ? substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' .
substr($cpf, 9, 2)
        : $cpf;
}
function format_date(?string $date): string
{
    $d = $date ? \DateTime::createFromFormat('Y-m-d', $date) : false;
    return $d ? $d->format('d/m/Y') : '';
}

// ===== Sessão, autenticação e CSRF =====
function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
        session_start();
    }
}
function current_user(): ?array
{
    start_session();
    return $_SESSION['user'] ?? null;
}
function is_admin(): bool
{
    return (current_user()['role'] ?? '') === 'admin';
}
function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}
function csrf_token(): string
{
    start_session();
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}
function check_csrf(): void
{
    start_session();
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403);
        exit('Token inválido');
    }
}
// flash(): com texto, guarda a mensagem; sem texto, devolve e apaga.
function flash(?string $msg = null): ?string
{
    start_session();
    if ($msg !== null) { $_SESSION['flash'] = $msg; return null; }
    $m = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $m;
}

// ===== Guardas de acesso (usadas nos controllers) =====
// require_login(): exige usuário logado; senão manda para o login.
function require_login(): void
{
    if (current_user() === null) {
        flash('Faça login para continuar.');
        redirect('auth/login');
    }
}
// require_admin(): exige login E perfil de administrador.
function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        exit('Acesso restrito ao administrador.');
    }
}