<h1><?= e($title) ?></h1>
<a href="<?= e(url('user/conteudo/' . (int) $comment['content_id'])) ?>">Voltar</a>

<form class="cadastro" method="post" action="<?= e(url('comment/update/' . (int) $comment['id'])) ?>">
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
    <label>Comentário
        <textarea name="body" rows="5" required maxlength="1000"><?= e($comment['body']) ?></textarea>
    </label>
    <?php if ($error): ?><p class="erro"><?= e($error) ?></p><?php endif; ?>
    <p><small>Depois de editado, o comentário volta a ficar pendente até o administrador aprová-lo.</small></p>
    <button type="submit">Salvar alterações</button>
</form>
