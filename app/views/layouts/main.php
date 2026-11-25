<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Projeto MVC') ?></title>
    <link rel="stylesheet" href="<?= e(asset('css/estilo.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/styles.css')) ?>">

</head>
<body class="body">
    <header class="topo">
        <img src="<?= e(asset('img/logo.png')) ?>" alt="Logo do projeto"
             width="40" height="40">
        <nav>
            <a href="<?= e(url('user/conteudo')) ?>">Inicio</a>
            <?php if (is_admin()): ?>
                <a href="<?= e(url('user/index')) ?>">Usuários</a>
                <a href="<?= e(url('user/create')) ?>">Novo usuário</a>
                <a href="<?= e(url('admin/index')) ?>">Painel admin</a>
            <?php endif; ?>
            <?php if (current_user()): ?>
                <a href="<?= e(url('user/perfil')) ?>">Minha conta</a>
                <form method="post" action="<?= e(url('auth/logout')) ?>" style="display:inline">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <button type="submit">Sair</button>
                </form>
            <?php else: ?>
                <a href="<?= e(url('auth/login')) ?>">Entrar</a>
                <a href="<?= e(url('user/create')) ?>">Cadastrar-se</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?php if ($msg = flash()): ?><p class="aviso"><?= e($msg) ?></p><?php endif; ?>
        <?= $content ?>
    </main>

    <script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>

