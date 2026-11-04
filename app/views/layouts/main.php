<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Projeto MVC') ?></title>

    <!-- Imagem opcional: adicione public/img/logo.png antes de usar. -->
    <link rel="stylesheet" href="<?= e(asset('css/estilo.css')) ?>">
</head>
<body>
    <header class="topo">
        <!-- <img src="<?= e(asset('img/logo.png')) ?>" alt="Logo do projeto"
             width="40" height="40"> -->
        <nav>
            <a href="<?= e(url('user/index')) ?>">Usuários</a>
            <a href="<?= e(url('user/create')) ?>">Novo usuário</a>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>
