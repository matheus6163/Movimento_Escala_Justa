<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Novo Conteúdo - Movimento Escala Justa</title>
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
    <style>
        body {
            background-color: #7293bc;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar-custom {
            background-color: #1e293b;
            padding: 15px 30px;
            display: flex;
            align-items: center;
        }

        .navbar-custom a {
            color: #ffffff;
            text-decoration: none;
            margin-right: 25px;
            font-size: 15px;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .form-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
        }

        .form-card h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: normal;
        }

        .mb-3 {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-salvar {
            background-color: #4a75bc;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-salvar:hover {
            background-color: #3b5fa0;
        }

        .link-voltar {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #4a75bc;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- Barra de navegação do painel com o link 'Início' apontando para o admin -->
<div class="navbar-custom">
    <a href="<?= url('admin/home') ?>"><strong>Logo</strong></a>
    <a href="<?= url('admin/home') ?>">Início</a>
    <a href="<?= url('admin') ?>">Usuários / Admin</a>
    <a href="<?= url('admin/store') ?>">Novo conteúdo</a>
</div>

    <div class="form-container">
        <div class="form-card">
            <h2>Cadastrar Conteúdo</h2>

            <form action="<?= url('admin/store') ?>" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>

                <div class="mb-3">
                    <label for="legenda" class="form-label">Legenda / Descrição</label>
                    <textarea class="form-control" id="legenda" name="legenda" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="imagem" class="form-label">Imagem</label>
                    <input type="file" class="form-control" id="imagem" name="imagem">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="publicado">Publicado</option>
                        <option value="rascunho">Rascunho</option>
                    </select>
                </div>

                <button type="submit" class="btn-salvar">Salvar</button>
                <a href="<?= url('admin') ?>" class="link-voltar">Voltar</a>

            </form>
        </div>
    </div>

</body>

</html>