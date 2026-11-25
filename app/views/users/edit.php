<h1><?= e($title) ?></h1>
<a href="<?= e(url('user/index')) ?>">Voltar</a>
<form method="post" action="<?= e(url('user/update/' . (int) $user['id'])) ?>" data-validate>
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

    <label>Nome
        <input name="name" required maxlength="100" value="<?= e($old['name'] ?? '') ?>">
    </label>
    <?php if (isset($errors['name'])): ?>
        <p class="erro"><?= e($errors['name']) ?></p>
    <?php endif; ?>

    <label>Sobrenome
        <input name="last_name" required maxlength="100" value="<?= e($old['last_name'] ?? '') ?>">
    </label>
    <?php if (isset($errors['last_name'])): ?>
        <p class="erro"><?= e($errors['last_name']) ?></p>
    <?php endif; ?>

    <label>E-mail
        <input type="email" name="email" required maxlength="150" value="<?= e($old['email'] ?? '') ?>">
    </label>
    <?php if (isset($errors['email'])): ?>
        <p class="erro"><?= e($errors['email']) ?></p>
    <?php endif; ?>

    <label>Nova senha (deixe em branco para manter a atual)
        <input type="password" name="password" maxlength="72" autocomplete="new-password">
    </label>
    <?php if (isset($errors['password'])): ?>
        <p class="erro"><?= e($errors['password']) ?></p>
    <?php endif; ?>

    <div class="acoes-form">
        <button type="submit">Salvar alterações</button>
        <a class="btn btn-sec" href="<?= e(url('user/index')) ?>">Cancelar</a>
    </div>
</form>
