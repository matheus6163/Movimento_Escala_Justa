<h1 class="titulo-perfil">Minha conta</h1>
<div class="perfil">
    <!-- RF001: ver e atualizar dados cadastrais -->
    <form class="cadastro" method="post" action="<?= e(url('user/perfilUpdate')) ?>">
        <h2>Meus dados</h2>
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

        <label>Nome
            <input name="name" required maxlength="100" value="<?= e($old['name'] ?? '') ?>">
        </label>
        <?php if (isset($errors['name'])): ?><p class="erro"><?= e($errors['name']) ?></p><?php endif; ?>

        <label>Sobrenome
            <input name="last_name" required maxlength="100" value="<?= e($old['last_name'] ?? '') ?>">
        </label>
        <?php if (isset($errors['last_name'])): ?><p class="erro"><?= e($errors['last_name']) ?></p><?php endif; ?>

        <label>E-mail
            <input type="email" name="email" required maxlength="150" value="<?= e($old['email'] ?? '') ?>">
        </label>
        <?php if (isset($errors['email'])): ?><p class="erro"><?= e($errors['email']) ?></p><?php endif; ?>

        <button type="submit">Salvar dados</button>
    </form>

    <!-- RF001: alterar senha -->
    <form class="cadastro" method="post" action="<?= e(url('user/senhaUpdate')) ?>">
        <h2>Alterar senha</h2>
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

        <label>Senha atual
            <input type="password" name="current_password" required autocomplete="current-password">
        </label>
        <?php if (isset($pwErrors['current_password'])): ?><p class="erro"><?= e($pwErrors['current_password']) ?></p><?php endif; ?>

        <label>Nova senha (mínimo 8 caracteres)
            <input type="password" name="new_password" required minlength="8" maxlength="72" autocomplete="new-password">
        </label>
        <?php if (isset($pwErrors['new_password'])): ?><p class="erro"><?= e($pwErrors['new_password']) ?></p><?php endif; ?>

        <label>Confirmar nova senha
            <input type="password" name="confirm_password" required minlength="8" maxlength="72" autocomplete="new-password">
        </label>
        <?php if (isset($pwErrors['confirm_password'])): ?><p class="erro"><?= e($pwErrors['confirm_password']) ?></p><?php endif; ?>

        <button type="submit">Alterar senha</button>
    </form>

    <!-- RF001: excluir a própria conta (não aparece para o administrador) -->
    <?php if (!$isAdmin): ?>
    <form class="cadastro" method="post" action="<?= e(url('user/perfilDestroy')) ?>"
        onsubmit="return confirm('Tem certeza? Sua conta, seus comentários e suas avaliações serão apagados.')">
        <h2>Excluir minha conta</h2>
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <p>Esta ação é permanente. Confirme com a sua senha.</p>
        <label>Senha
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit">Excluir minha conta</button>
    </form>
</div>
<?php endif; ?>