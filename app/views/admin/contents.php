<div class="cabecalho-pagina">
    <h1><?= e($title) ?></h1>
    <a href="<?= e(url('admin/index')) ?>">Voltar</a>
    <a class="btn" href="<?= e(url('admin/contentCreate')) ?>">+ Novo conteúdo</a>
</div>

<table class="tabela">
    <thead>
        <tr><th>Título</th><th>Situação</th><th>Criado em</th><th>Ações</th></tr>
    </thead>
    <tbody>
    <?php foreach ($contents as $c): ?>
        <tr>
            <td><?= e($c['title']) ?></td>
            <td><?= $c['status'] === 'published' ? 'Publicado' : 'Rascunho' ?></td>
            <td><?= e(date('d/m/Y H:i', strtotime($c['created_at']))) ?></td>
            <td class="acoes">
                <a class="btn" href="<?= e(url('admin/contentEdit/' . (int) $c['id'])) ?>">Editar</a>
                <form method="post" style="display:inline"
                      action="<?= e(url('admin/contentDelete/' . (int) $c['id'])) ?>"
                      onsubmit="return confirm('Excluir este conteúdo?')">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                    <button type="submit" class="btn btn-sec">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$contents): ?><tr><td colspan="4">Nenhum conteúdo ainda.</td></tr><?php endif; ?>
    </tbody>
</table>
