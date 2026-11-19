<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Início Admin - Movimento Escala Justa</title>
    <!-- Importação do arquivo de estilo principal do projeto -->
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
    <style>
        /* Estilização da página inicial do admin preservando a identidade visual da equipe */
        body {
            background-color: #7293bc;
            /* Tom de fundo azul acinzentado padrão */
            margin: 0;
            font-family: Arial, sans-serif;
            color: #333;
        }

        /* Barra de navegação superior escura */
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

        /* Container centralizado para evitar distorções de layout */
        .admin-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Grid de cartões informativos e de acesso rápido */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        /* Componente de card branco padronizado */
        .admin-card {
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .admin-card h2 {
            margin-top: 0;
            color: #1e293b;
            font-size: 20px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 10px;
        }

        .admin-card p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Botões de ação interna */
        .btn-admin {
            display: inline-block;
            background-color: #4a75bc;
            color: white;
            padding: 10px 18px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
        }

        .btn-admin:hover {
            background-color: #3b5fa0;
        }
    </style>
</head>

<body>

    <!-- Barra de Navegação Superior do Painel -->
    <div class="navbar-custom">
        <a href="<?= url('admin/home') ?>"><strong>Logo</strong></a>
        <a href="<?= url('admin/home') ?>">Início</a>
        <a href="<?= url('admin') ?>">Usuários / Admin</a>
        <a href="<?= url('admin/store') ?>">Novo conteúdo</a>
    </div>

    <!-- Container Principal do Painel Administrativo -->
    <div class="admin-container">

        <!-- Cabeçalho da Página Inicial Interna -->
        <div class="admin-card" style="margin-bottom: 25px;">
            <h1 style="margin: 0 0 10px 0; color: #1e293b; font-size: 24px;">Visão Geral do Sistema</h1>
            <p style="margin: 0;">Gerencie publicações, acompanhe o fluxo de conteúdos e administre as informações do projeto de forma rápida e segura.</p>
        </div>

        <!-- Grade de Atalhos e Módulos para o Administrador -->
        <div class="dashboard-grid">

            <!-- Card de Gerenciamento de Conteúdos -->
            <div class="admin-card">
                <h2>Gerenciar Conteúdos</h2>
                <p>Visualize a lista completa de publicações cadastradas, verifique o status de cada postagem e realize exclusões se necessário.</p>
                <a href="<?= url('admin') ?>" class="btn-admin">Acessar Conteúdos</a>
            </div>

            <!-- Card de Cadastro de Nova Publicação -->
            <div class="admin-card">
                <h2>Nova Publicação</h2>
                <p>Adicione rapidamente novos artigos, avisos ou imagens para atualizar o portal de informações do movimento.</p>
                <a href="<?= url('admin/store') ?>" class="btn-admin">+ Criar Publicação</a>
            </div>

            <!-- Card de Informações / Sistema -->
            <div class="admin-card">
                <h2>Atalhos do Sistema</h2>
                <p>Retorne a qualquer momento para a página principal pública ou utilize o menu superior para navegar entre as seções.</p>
                <a href="<?= url('user/conteudo') ?>" class="btn-admin" style="background-color: #475569;">Ver Site Público</a>
            </div>

        </div>

    </div>

    <!-- Card de Moderação de Comentários [RF011] -->
    <div class="admin-card">
        <h2>Moderar Comentários</h2>
        <p>Acompanhe, aprove, oculte ou exclua os comentários realizados pelos usuários nas publicações do site.</p>
        <a href="<?= url('admin/comentarios') ?>" class="btn-admin" style="background-color: #d97706;">Gerenciar Comentários</a>
    </div>
</body>

</html>