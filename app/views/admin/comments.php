<div class="cabecalho-pagina">
    <h1><?= e($title) ?></h1>
    <a href="<?= e(url('admin/index')) ?>">Voltar</a>
</div>
<p>
    Filtrar:
    <a href="<?= e(url('admin/comments')) ?>">Todos</a> |
    <a href="<?= e(url('admin/comments?status=pending')) ?>">Pendentes</a> |
    <a href="<?= e(url('admin/comments?status=approved')) ?>">Aprovados</a>
</p>

<table class="tabela">
    <thead>
        <tr><th>Usuário</th><th>Conteúdo</th><th>Comentário</th><th>Situação</th><th>Ações</th></tr>
    </thead>
    <tbody>
    <?php foreach ($comments as $cm): ?>
        <tr>
            <td><?= e($cm['user_name']) ?><br><small><?= e($cm['user_email']) ?></small></td>
            <td><?= e($cm['content_title']) ?></td>
            <td><?= nl2br(e($cm['body'])) ?></td>
            <td><?= $cm['status'] === 'approved' ? 'Aprovado' : 'Pendente' ?></td>
            <td class="acoes">
                <?php if ($cm['status'] === 'pending'): ?>
                <form method="post" style="display:inline"
                      action="<?= e(url('admin/commentApprove/' . (int) $cm['id'])) ?>">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                    <button type="submit" class="btn">Aprovar</button>
                </form>
                <?php endif; ?>
                <a class="btn btn-sec" href="<?= e(url('admin/commentEdit/' . (int) $cm['id'])) ?>">Editar</a>
                <form method="post" style="display:inline"
                      action="<?= e(url('admin/commentDelete/' . (int) $cm['id'])) ?>"
                      onsubmit="return confirm('Remover este comentário?')">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                    <button type="submit" class="btn btn-sec">Remover</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$comments): ?><tr><td colspan="5">Nenhum comentário.</td></tr><?php endif; ?>
    </tbody>
</table>
