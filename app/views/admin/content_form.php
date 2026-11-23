<h1><?= e($title) ?></h1>
<a href="<?= e(url('admin/contents')) ?>">Voltar</a>

<form class="cadastro" method="post" enctype="multipart/form-data"
      action="<?= e(url($action)) ?>">
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

    <label>Título
        <input name="title" required maxlength="150" value="<?= e($old['title'] ?? '') ?>">
    </label>
    <?php if (isset($errors['title'])): ?><p class="erro"><?= e($errors['title']) ?></p><?php endif; ?>

    <label>Legenda
        <input name="caption" required maxlength="300" value="<?= e($old['caption'] ?? '') ?>">
    </label>
    <?php if (isset($errors['caption'])): ?><p class="erro"><?= e($errors['caption']) ?></p><?php endif; ?>

    <label>Texto
        <textarea name="body" rows="8"><?= e($old['body'] ?? '') ?></textarea>
    </label>

    <label>Imagem (JPG, PNG ou WEBP, até 2 MB)
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
    </label>
    <?php if (!empty($old['image'])): ?>
        <p>Imagem atual: <img src="<?= e(url('uploads/' . $old['image'])) ?>" alt="" height="60"></p>
    <?php endif; ?>
    <?php if (isset($errors['image'])): ?><p class="erro"><?= e($errors['image']) ?></p><?php endif; ?>

    <label>Situação
        <select name="status">
            <option value="draft"     <?= ($old['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Rascunho</option>
            <option value="published" <?= ($old['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publicado</option>
        </select>
    </label>
    <?php if (isset($errors['status'])): ?><p class="erro"><?= e($errors['status']) ?></p><?php endif; ?>

    <button type="submit">Salvar</button>
</form>
