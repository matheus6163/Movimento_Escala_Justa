<a href="<?= e(url('user')) ?>">Voltar</a>

<h1><?= e($title) ?></h1>

<form method="post" action="<?= e(url('user/store')) ?>" data-validate>
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

    <label>Nome
        <input name="name" required maxlength="100"
               value="<?= e($old['name'] ?? '') ?>">
    </label>
    <?php if (isset($errors['name'])): ?>
        <p class="erro"><?= e($errors['name']) ?></p>
    <?php endif; ?>

    <label>E-mail
        <input type="email" name="email" required maxlength="150"
               value="<?= e($old['email'] ?? '') ?>">
    </label>
    <?php if (isset($errors['email'])): ?>
        <p class="erro"><?= e($errors['email']) ?></p>
    <?php endif; ?>

    <button type="submit">Salvar</button>
</form>
