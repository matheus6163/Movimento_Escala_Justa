<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Conteúdos - Painel Administrativo</title>
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
            max-width: 1100px;
            margin: 0 auto;
        }

        .content-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-acao {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            text-decoration: none;
            color: #fff;
            margin-right: 5px;
        }

        .btn-editar {
            background-color: #4a75bc;
        }

        .btn-excluir {
            background-color: #dc3545;
        }

        .btn-novo {
            background-color: #16a34a;
            padding: 10px 18px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            float: right;
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
        <div class="content-card">
            <div style="overflow: hidden; margin-bottom: 25px;">
                <div style="float: left;">
                    <h2 style="margin: 0 0 5px 0; color: #1e293b;">Gerenciamento de Conteúdos</h2>
                    <p style="margin: 0; color: #64748b; font-size: 14px;">Visualize, edite ou exclua as publicações do site.</p>
                </div>
                <div style="float: right;">
                    <a href="<?= url('admin/store') ?>" class="btn-novo">+ Nova Publicação</a>
                </div>
            </div>

            <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr style="border-bottom: 2px solid #dee2e6; color: #334155;">
                        <th style="padding: 12px; text-align: left;">ID</th>
                        <th style="padding: 12px; text-align: left;">Título</th>
                        <th style="padding: 12px; text-align: left;">Status</th>
                        <th style="padding: 12px; text-align: left;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($conteudos)): ?>
                        <?php foreach ($conteudos as $c): ?>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;"><?= $c['id'] ?></td>
                                <td style="padding: 12px; font-weight: bold;"><?= htmlspecialchars($c['titulo'] ?? '') ?></td>
                                <td style="padding: 12px; text-transform: capitalize;"><?= htmlspecialchars($c['status'] ?? 'publicado') ?></td>
                                <td style="padding: 12px;">
                                    <a href="<?= url('admin/edit/' . $c['id']) ?>" class="btn-acao btn-editar">Editar</a>
                                    <a href="<?= url('admin/delete/' . $c['id']) ?>" class="btn-acao btn-excluir" onclick="return confirm('Deseja realmente excluir esta publicação?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="padding: 30px; text-align: center; color: #64748b;">
                                Nenhuma publicação cadastrada no momento.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>