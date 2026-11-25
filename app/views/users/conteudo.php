<div class="container mt-5">
    <div class="row">
        <div class="">
            <a href="<?= e(url('user/conteudo')) ?>">&larr; Todos os conteúdos</a>

            <article class="mt-3">
                <header class="mb-4">
                    <h1 class="fw-bolder mb-1"><?= e($item['title']) ?></h1>
                    <div class="text-muted fst-italic mb-2">
                        Publicado em <?= e(date('d/m/Y', strtotime($item['created_at']))) ?>
                        <?php if (!$isPublished): ?> — <strong>RASCUNHO (só o administrador vê)</strong><?php endif; ?>
                    </div>
                    <p class="fs-5"><?= e($item['caption']) ?></p>
                    <?php if ($isAdmin): ?>
                        <a class="btn btn-sec" href="<?= e(url('admin/contentEdit/' . (int) $item['id'])) ?>">Editar conteúdo</a>
                    <?php endif; ?>
                </header>

                <?php if (!empty($item['image'])): ?>
                    <figure class="mb-4">
                        <img class="img-fluid rounded" src="<?= e(url('uploads/' . $item['image'])) ?>"
                             alt="<?= e($item['title']) ?>">
                    </figure>
                <?php endif; ?>

                <section class="mb-5">
                    <p class="fs-5 mb-4"><?= nl2br(e($item['body'] ?? '')) ?></p>
                </section>
            </article>

            <?php if ($isPublished): ?>

            <!-- RF012/RF013/RF014/RF016: curtir, descurtir, trocar, remover e contadores -->
            <section class="mb-5">
                <p>
                    👍 <strong><?= (int) $counts['likes'] ?></strong> &nbsp;
                    👎 <strong><?= (int) $counts['dislikes'] ?></strong>
                </p>
                <form method="post" style="display:inline"
                      action="<?= e(url('rating/store/' . (int) $item['id'])) ?>">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                    <input type="hidden" name="value" value="1">
                    <button type="submit" class="btn <?= $myVote === 1 ? '' : 'btn-sec' ?>">Curtir</button>
                </form>
                <form method="post" style="display:inline"
                      action="<?= e(url('rating/store/' . (int) $item['id'])) ?>">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                    <input type="hidden" name="value" value="-1">
                    <button type="submit" class="btn <?= $myVote === -1 ? '' : 'btn-sec' ?>">Descurtir</button>
                </form>
                <?php if ($myVote !== null): ?>
                    <form method="post" style="display:inline"
                          action="<?= e(url('rating/remove/' . (int) $item['id'])) ?>">
                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                        <button type="submit" class="btn btn-sec">Remover minha avaliação</button>
                    </form>
                <?php endif; ?>
            </section>

            <!-- RF002/RF003/RF004/RF005: comentar, ver, editar e excluir o próprio -->
            <section class="mb-5">
                <h2 class="fw-bolder mb-3">Comentários</h2>
                <div class="card bg-light">
                    <div class="card-body">
                        <form class="mb-4" method="post"
                              action="<?= e(url('comment/store/' . (int) $item['id'])) ?>">
                            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                            <textarea class="form-control" name="body" rows="3" maxlength="1000" required
                                      placeholder="Participe da discussão e deixe seu comentário!"></textarea>
                            <button type="submit" class="btn">Comentar</button>
                            <small>Seu comentário aparece para todos depois de aprovado.</small>
                        </form>

                        <?php foreach ($comments as $cm): ?>
                            <div class="d-flex mb-4">
                                <div class="ms-3">
                                    <div class="fw-bold"><?= e($cm['user_name']) ?>
                                        <small class="text-muted">
                                            — <?= e(date('d/m/Y H:i', strtotime($cm['created_at']))) ?>
                                        </small>
                                        <?php if ($cm['status'] === 'pending'): ?>
                                            <small class="text-muted">(aguardando aprovação — só você vê)</small>
                                        <?php endif; ?>
                                    </div>
                                    <?= nl2br(e($cm['body'])) ?>

                                    <?php if ((int) $cm['user_id'] === $userId): ?>
                                        <div>
                                            <a class="btn btn-sec"
                                               href="<?= e(url('comment/edit/' . (int) $cm['id'])) ?>">Editar</a>
                                            <form method="post" style="display:inline"
                                                  action="<?= e(url('comment/destroy/' . (int) $cm['id'])) ?>"
                                                  onsubmit="return confirm('Excluir seu comentário?')">
                                                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                                <button type="submit" class="btn btn-sec">Excluir</button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (!$comments): ?><p>Ainda não há comentários.</p><?php endif; ?>
                    </div>
                </div>
            </section>

            <?php endif; ?>
        </div>
    </div>
</div>

