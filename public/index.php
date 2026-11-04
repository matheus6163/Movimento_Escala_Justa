<?php
declare(strict_types=1);

// 0) Caminho base: '/projeto_mvc' em subpasta, '' em VirtualHost
$base = dirname(dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim(str_replace('\\', '/', $base), '/'));


// 1) Autoload
spl_autoload_register(function (string $class) {
    $file = __DIR__ . '/../app/'
        . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
    if (is_file($file))
        require $file;
});

require __DIR__ . '/../app/helpers.php';

// 2) Interpretação da URL
$url = explode('/', trim($_GET['url'] ?? 'user/index', '/'));
$controller = 'App\\Controllers\\' . ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';
$params = array_slice($url, 2);

// 3) Validação e despacho
if (!class_exists($controller) || !method_exists($controller, $method)) {
    http_response_code(404);
    exit("Página não encontrada");
}

(new $controller())->$method(...$params);