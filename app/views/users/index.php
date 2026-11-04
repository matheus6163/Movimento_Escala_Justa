<a href="<?= e(url('user')) ?>">Voltar</a>

<h1><?= e($title) ?></h1>

<ul class="lista">
<?php foreach ($users as $u): ?>
    <li>
        <a href="<?= e(url('user/show/' . (int) $u['id'])) ?>">
            <?= e($u['name']) ?>
        </a>
        (<?= e($u['email']) ?>)
    </li>
<?php endforeach; ?>
</ul>
