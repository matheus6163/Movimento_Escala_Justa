<form class="cadastro" method="post" action="<?= e(url('auth/login')) ?>">
    <h2>Entrar</h2>
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

    <?php if ($error): ?><p class="erro"><?= e($error) ?></p><?php endif; ?>

    <label>E-mail
        <input type="email" name="email" required maxlength="150" value="<?= e($email) ?>">
    </label>
    <label>Senha
        <input type="password" name="password" required>
    </label>
    <button type="submit">Entrar</button>
</form>
