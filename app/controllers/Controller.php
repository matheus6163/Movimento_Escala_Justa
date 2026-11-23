<?php
namespace App\Controllers;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        $base = __DIR__ . '/../views/';

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

    /** Ações que alteram dados: só POST e com token CSRF. */
    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido');
        }
        check_csrf();
    }
}
