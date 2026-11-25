<div class="cabecalho-pagina"><h1><?= e($title) ?></h1></div>
<p>Conteúdos cadastrados: <strong><?= (int) $contents ?></strong></p>
<p>Comentários: <strong><?= (int) $comments ?></strong>
   (<strong><?= (int) $pending ?></strong> aguardando aprovação)</p>
<p>
    <a class="btn" href="<?= e(url('admin/contents')) ?>">Conteúdos</a>
    <a class="btn" href="<?= e(url('admin/comments')) ?>">Comentários</a>
    <a class="btn" href="<?= e(url('admin/ratings')) ?>">Avaliações</a>
</p>