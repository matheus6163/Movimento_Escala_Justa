<div class="cabecalho-pagina">
    <h1><?= e($title) ?></h1>
    <a href="<?= e(url('admin/index')) ?>">Voltar</a>
</div>

<h2>Totais por conteúdo</h2>
<table class="tabela">
    <thead><tr><th>Conteúdo</th><th>Curtidas</th><th>Descurtidas</th></tr></thead>
    <tbody>
    <?php foreach ($totals as $t): ?>
        <tr><td><?= e($t['title']) ?></td><td><?= (int) $t['likes'] ?></td><td><?= (int) $t['dislikes'] ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h2>Quem avaliou</h2>
<table class="tabela">
    <thead><tr><th>Usuário</th><th>Conteúdo</th><th>Avaliação</th><th>Data</th></tr></thead>
    <tbody>
    <?php foreach ($details as $d): ?>
        <tr>
            <td><?= e($d['user_name']) ?> <small>(<?= e($d['user_email']) ?>)</small></td>
            <td><?= e($d['content_title']) ?></td>
            <td><?= (int) $d['value'] === 1 ? 'Curtiu' : 'Descurtiu' ?></td>
            <td><?= e(date('d/m/Y H:i', strtotime($d['created_at']))) ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$details): ?><tr><td colspan="4">Nenhuma avaliação ainda.</td></tr><?php endif; ?>
    </tbody>
</table>
