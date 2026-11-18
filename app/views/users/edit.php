<h1><?= e($title) ?></h1>
<a href="<?= e(url('user')) ?>">Voltar</a>
<form method="post" action="<?= url('user/update/' . (int) $user['id']) ?>" data-
validate>
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

    <label>Nome
        <input name="name" value="<?= e($old['name'] ?? '') ?>">
    </label>
    <?php if (isset($errors['name'])): ?>
        <p class="erro"><?= e($errors['name']) ?></p>
    <?php endif; ?>

    <label>Sobrenome
        <input name="last_name" value="<?= e($old['last_name'] ?? '') ?>">
    </label>
    <?php if (isset($errors['last_name'])): ?>
        <p class="erro"><?= e($errors['last_name']) ?></p>
    <?php endif; ?>

    <label>E-mail
        <input name="email" value="<?= e($old['email'] ?? '') ?>">
    </label>
    <?php if (isset($errors['email'])): ?>
        <p class="erro"><?= e($errors['email']) ?></p>
    <?php endif; ?>

    <div class="acoes-form">
        <button type="submit">Salvar alterações</button>
        <a class="btn btn-sec" href="<?= url('user/index') ?>">Cancelar</a>
    </div>
</form>