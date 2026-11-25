<form class="cadastro" method="post" action="<?= e(url('user/store')) ?>" data-validate>
    <h2>Cadastrar</h2>

    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

    <label>Nome
        <input name="name" required maxlength="100"
               value="<?= e($old['name'] ?? '') ?>">
    </label>
    <?php if (isset($errors['name'])): ?>
        <p class="erro"><?= e($errors['name']) ?></p>
    <?php endif; ?>

    <label>Sobrenome
        <input name="last_name" required maxlength="100"
               value="<?= e($old['last_name'] ?? '') ?>">
    </label>
    <?php if (isset($errors['last_name'])): ?>
        <p class="erro"><?= e($errors['last_name']) ?></p>
    <?php endif; ?>

    <label>E-mail
        <input type="email" name="email" required maxlength="150"
               value="<?= e($old['email'] ?? '') ?>">
    </label>
    <?php if (isset($errors['email'])): ?>
        <p class="erro"><?= e($errors['email']) ?></p>
    <?php endif; ?>

    <label>Senha (mínimo 8 caracteres)
        <input type="password" name="password" required minlength="8" maxlength="72"
               autocomplete="new-password">
    </label>
    <?php if (isset($errors['password'])): ?>
        <p class="erro"><?= e($errors['password']) ?></p>
    <?php endif; ?>

    <button type="submit">Salvar</button>
</form>

