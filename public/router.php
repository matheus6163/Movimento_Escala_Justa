<?php
// Uso: php -S localhost:8000 -t public public/router.php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$file = is_string($path) ? realpath(__DIR__ . $path) : false;
if ($file !== false && str_starts_with($file, __DIR__ . DIRECTORY_SEPARATOR)
    && is_file($file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'php') {
    return false;                       // serve CSS, JS, imagens etc.
}

$_SERVER['SCRIPT_NAME'] = '/index.php'; // BASE_URL ficará vazio
require __DIR__ . '/index.php';
