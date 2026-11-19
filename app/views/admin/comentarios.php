<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Moderar Comentários - Movimento Escala Justa</title>
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
    <style>
        /* Mantém a mesma identidade visual das outras telas do painel */
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

        /* Etiquetas de Status */
        .badge-status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
        }

        .status-aprovado {
            background-color: #16a34a;
        }

        .status-oculto {
            background-color: #64748b;
        }

        .status-pendente {
            background-color: #d97706;
        }

        /* Botões de Ação */
        .btn-acao {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 13px;
            text-decoration: none;
            color: #fff;
            margin-right: 4px;
            border: none;
            cursor: pointer;
        }

        .btn-aprovar {
            background-color: #16a34a;
        }

        .btn-esconder {
            background-color: #64748b;
        }

        .btn-editar {
            background-color: #4a75bc;
        }

        .btn-excluir {
            background-color: #dc3545;
        }
    </style>
</head>

<body>

    <!-- Barra de Navegação Superior Padrão do Admin -->
    <div class="navbar-custom">
        <a href="<?= url('admin/home') ?>"><strong>Logo</strong></a>
        <a href="<?= url('admin/home') ?>">Início</a>
        <a href="<?= url('admin') ?>">Conteúdos</a>
        <a href="<?= url('admin/store') ?>">Novo conteúdo</a>
        <a href="<?= url('admin/comentarios') ?>">Comentários</a>
    </div>

    <div class="main-container">
        <div class="content-card">
            <div style="margin-bottom: 20px;">
                <h2 style="margin: 0 0 5px 0; color: #1e293b;">Moderação de Comentários</h2>
                <p style="margin: 0; color: #64748b; font-size: 14px;">
                    Visualize, aprove, esconda, edite ou exclua os comentários enviados pelos usuários na plataforma.
                </p>
            </div>

            <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="border-bottom: 2px solid #dee2e6; color: #334155;">
                        <th style="padding: 12px; text-align: left;">ID</th>
                        <th style="padding: 12px; text-align: left;">Autor</th>
                        <th style="padding: 12px; text-align: left;">Comentário</th>
                        <th style="padding: 12px; text-align: left;">Status</th>
                        <th style="padding: 12px; text-align: left;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($comentarios)): ?>
                        <?php foreach ($comentarios as $c): ?>
                            <?php $status = $c['status'] ?? 'aprovado'; ?>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;"><?= $c['id'] ?></td>
                                <td style="padding: 12px; font-weight: bold;">
                                    <?= htmlspecialchars($c['nome_usuario'] ?? 'Usuário') ?>
                                </td>
                                <td style="padding: 12px;">
                                    <!-- Texto exibido normalmente -->
                                    <span id="texto-<?= $c['id'] ?>">
                                        <?= nl2br(htmlspecialchars($c['texto'] ?? '')) ?>
                                    </span>

                                    <!-- Formulário oculto para edição rápida do comentário -->
                                    <form id="form-edit-<?= $c['id'] ?>" action="<?= url('admin/comentarios/editar') ?>" method="POST" style="display: none; margin-top: 8px;">
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <textarea name="texto" rows="2" style="width: 100%; padding: 6px; border: 1px solid #cbd5e1; border-radius: 4px;"><?= htmlspecialchars($c['texto'] ?? '') ?></textarea>
                                        <div style="margin-top: 5px;">
                                            <button type="submit" class="btn-acao btn-aprovar">Salvar</button>
                                            <button type="button" class="btn-acao btn-esconder" onclick="toggleEditar(<?= $c['id'] ?>)">Cancelar</button>
                                        </div>
                                    </form>
                                </td>
                                <td style="padding: 12px;">
                                    <span class="badge-status status-<?= $status ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                                <td style="padding: 12px; white-space: nowrap;">
                                    <!-- Alterna entre Aprovar (exibir) e Esconder (ocultar) -->
                                    <?php if ($status === 'oculto' || $status === 'pendente'): ?>
                                        <a href="<?= url('admin/comentarios/status/' . $c['id'] . '/aprovar') ?>" class="btn-acao btn-aprovar">Aprovar / Exibir</a>
                                    <?php else: ?>
                                        <a href="<?= url('admin/comentarios/status/' . $c['id'] . '/esconder') ?>" class="btn-acao btn-esconder">Esconder</a>
                                    <?php endif; ?>

                                    <!-- Botão que abre o campo de edição -->
                                    <button type="button" class="btn-acao btn-editar" onclick="toggleEditar(<?= $c['id'] ?>)">Editar</button>

                                    <!-- Botão de exclusão permanente -->
                                    <a href="<?= url('admin/comentarios/excluir/' . $c['id']) ?>" class="btn-acao btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este comentário permanentemente?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="padding: 30px; text-align: center; color: #64748b;">
                                Nenhum comentário registrado no momento.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script simples para mostrar/esconder o campo de edição inline -->
    <script>
        function toggleEditar(id) {
            const texto = document.getElementById('texto-' + id);
            const form = document.getElementById('form-edit-' + id);
            if (form.style.display === 'none') {
                form.style.display = 'block';
                texto.style.display = 'none';
            } else {
                form.style.display = 'none';
                texto.style.display = 'inline';
            }
        }
    </script>

</body>

</html>