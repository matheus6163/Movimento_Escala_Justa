<?php
// Blindagem contra variáveis não definidas
$conteudo = $conteudo ?? [
    'id' => '',
    'titulo' => '',
    'legenda' => '',
    'status' => 'publicado',
    'imagem' => ''
];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Conteúdo - Movimento Escala Justa</title>
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
    <style>
        body {
            background-color: #7293bc;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #333;
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

        .navbar-custom a:hover {
            text-decoration: underline;
        }

        .main-container {
            padding: 40px 20px;
            max-width: 750px;
            margin: 0 auto;
        }

        .form-card {
            background: #ffffff;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            color: #1e293b;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .btn-salvar {
            background-color: #4a75bc;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-cancelar {
            background-color: #64748b;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 15px;
            margin-left: 8px;
        }
    </style>
</head>

<body>

    <div class="navbar-custom">
        <a href="<?= url('admin/home') ?>"><strong>Logo</strong></a>
        <a href="<?= url('admin/home') ?>">Início</a>
        <a href="<?= url('admin') ?>">Conteúdos</a>
        <a href="<?= url('admin/store') ?>">Novo conteúdo</a>
        <a href="<?= url('admin/comentarios') ?>">Comentários</a>
    </div>

    <div class="main-container">
        <div class="form-card">
            <h2 style="margin-top: 0; color: #1e293b;">Editar Publicação</h2>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 25px;">
                Altere as informações abaixo ou mude o status de visibilidade no site.
            </p>

            <form action="<?= url('admin/update/' . ($conteudo['id'] ?? '')) ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título da Publicação</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" value="<?= htmlspecialchars($conteudo['titulo'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="legenda">Texto / Legenda</label>
                    <textarea id="legenda" name="legenda" rows="5" class="form-control" required><?= htmlspecialchars($conteudo['legenda'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status de Exibição</label>
                    <select id="status" name="status" class="form-control">
                        <option value="publicado" <?= ($conteudo['status'] ?? '') === 'publicado' ? 'selected' : '' ?>>Publicado (Visível no site)</option>
                        <option value="rascunho" <?= ($conteudo['status'] ?? '') === 'rascunho' ? 'selected' : '' ?>>Rascunho (Oculto do público)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="imagem">Substituir Imagem (Opcional)</label>
                    <?php if (!empty($conteudo['imagem'])): ?>
                        <p style="font-size: 13px; color: #64748b; margin: 4px 0 8px 0;">
                            Imagem atual: <strong><?= htmlspecialchars($conteudo['imagem']) ?></strong>
                        </p>
                    <?php endif; ?>
                    <input type="file" id="imagem" name="imagem" class="form-control" accept="image/*">
                </div>

                <div style="margin-top: 25px;">
                    <button type="submit" class="btn-salvar">Salvar Alterações</button>
                    <a href="<?= url('admin') ?>" class="btn-cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>