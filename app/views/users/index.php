<div class="cabecalho-pagina">
    <h1><?= e($title) ?></h1>
    <a href="<?= e(url('user')) ?>">Voltar</a>
    <a class="btn" href="<?= e(url('user/create')) ?>">+ Novo usuário</a>
</div>

<table class="tabela">
    <thead>
        <tr>
            <th>Nome completo</th>
            <th>E-mail</th>
            <th>Perfil</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= e($u['name'] . ' ' . $u['last_name']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td><?= ($u['role'] ?? 'user') === 'admin' ? 'Administrador' : 'Usuário' ?></td>
            <td class="acoes">
                <a class="btn btn-sec" href="<?= e(url('user/show/' . (int) $u['id'])) ?>">Ver</a>
                <a class="btn" href="<?= e(url('user/edit/' . (int) $u['id'])) ?>">Editar</a>
                <form method="post" style="display:inline"
                      action="<?= e(url('user/destroy/' . (int) $u['id'])) ?>"
                      onsubmit="return confirm('Excluir este usuário? Os comentários e avaliações dele também serão apagados.')">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                    <button type="submit" class="btn btn-sec">Excluir</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$users): ?><tr><td colspan="4">Nenhum usuário.</td></tr><?php endif; ?>
    </tbody>
</table>

