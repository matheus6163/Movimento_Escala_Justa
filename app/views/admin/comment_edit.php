<h1><?= e($title) ?></h1>
<a href="<?= e(url('admin/comments')) ?>">Voltar</a>

<form class="cadastro" method="post" action="<?= e(url('admin/commentUpdate/' . (int) $comment['id'])) ?>">
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
    <label>Comentário
        <textarea name="body" rows="5" required maxlength="1000"><?= e($comment['body']) ?></textarea>
    </label>
    <?php if ($error): ?><p class="erro"><?= e($error) ?></p><?php endif; ?>
    <button type="submit">Salvar alterações</button>
</form>
