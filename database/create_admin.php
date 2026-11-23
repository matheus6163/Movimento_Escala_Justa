<?php
// Uso (no terminal, na raiz do projeto): php database/create_admin.php
if (PHP_SAPI !== 'cli') { exit('Execute pelo terminal.'); }
require __DIR__ . '/../app/models/Database.php';

function ask(string $label): string { echo $label; return trim((string) fgets(STDIN)); }

$name  = ask('Nome: ');
$email = ask('E-mail: ');
$pass  = ask('Senha (mín. 8 caracteres): ');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 8 || $name === '') {
    exit("Dados inválidos.\n");
}

$stmt = App\Models\Database::connect()->prepare(
    "INSERT INTO users (name, last_name, email, password_hash, role)
     VALUES (?, 'Admin', ?, ?, 'admin')
     ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), role = 'admin'"
);
$stmt->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
echo "Administrador criado/atualizado.\n";
