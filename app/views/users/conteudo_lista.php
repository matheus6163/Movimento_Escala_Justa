<div class="container mt-5">
    <h1 class="fw-bolder mb-4"><?= e($title) ?></h1>

    <?php foreach ($contents as $c): ?>
        <article class="mb-5">
            <?php if (!empty($c['image'])): ?>
                <figure class="mb-3">
                    <img class="img-fluid rounded" style="max-height:300px"
                         src="<?= e(url('uploads/' . $c['image'])) ?>" alt="<?= e($c['title']) ?>">
                </figure>
            <?php endif; ?>

            <h2 class="fw-bolder mb-1">
                <a href="<?= e(url('user/conteudo/' . (int) $c['id'])) ?>"><?= e($c['title']) ?></a>
                <?php if ($c['status'] !== 'published'): ?>
                    <small class="text-muted">(rascunho)</small>
                <?php endif; ?>
            </h2>
            <div class="text-muted fst-italic mb-2">
                Publicado em <?= e(date('d/m/Y', strtotime($c['created_at']))) ?>
                &nbsp;|&nbsp; 👍 <?= (int) $c['likes'] ?> &nbsp; 👎 <?= (int) $c['dislikes'] ?>
            </div>
            <p class="fs-5"><?= e($c['caption']) ?></p>

            <a class="btn" href="<?= e(url('user/conteudo/' . (int) $c['id'])) ?>">Ler mais</a>
            <?php if ($isAdmin): ?>
                <a class="btn btn-sec" href="<?= e(url('admin/contentEdit/' . (int) $c['id'])) ?>">Editar</a>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>

    <?php if (!$contents): ?>
        <p>Nenhum conteúdo publicado ainda.</p>
    <?php endif; ?>
</div>