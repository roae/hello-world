<article id="article-detail">
  <div class="article-banner <?= (isset($record['Foto']['id'])) ? 'with-image': '' ?>" style="<?= (isset($record['Foto']['id'])) ? 'background-image: url('.$record['Foto']['big'].')': '' ?>">
    <div class="col-container">
      <div class="info">
        <small class="date">
          Creado: 22 Enero, 2015 | en Travels
        </small>

        <header>
          <h1><?= $record['Article']['titulo'] ?></h1>
        </header>
      </div>
    </div>
  </div>

  <div class="col-container">
    <div class="article-content">
      <?= $record['Article']['contenido'] ?>
    </div>

    <div class="tags">
      <ul>

        <?php foreach ($record['Tag'] as $tag): ?>

          <li>
            <?= $this->Html->link($tag['nombre'], array('controller' => 'articles', 'action' => 'index', 'tag_slug' => $tag['slug']), array('class' => 'tag')) ?>
          </li>

        <?php endforeach; ?>

      </ul>
    </div>

    <div class="shares">
      <strong>¡Compártelo con tus amigos!</strong>

      <ul>
        <li>
          <a class="tw" href="">Twitter</a>
        </li>

        <li>
          <a class="fb" href="">Facebook</a>
        </li>

        <li>
          <a class="gp" href="">G+</a>
        </li>

        <li>
          <a class="pn" href="">Pinterest</a>
        </li>
      </ul>
    </div>

    <div class="related-posts">

      <strong>Artículos relacionados</strong>

      <ul>

        <?php foreach ($record['Related'] as $related): ?>

          <li>
            <header>
              <h2>
                <?= $this->Html->link($related['titulo'], array('controller' => 'articles', 'action' => 'view', 'id' => $related['id'], 'slug' => $related['slug'])) ?>
              </h2>
            </header>

            <span class="date">22 Enero, 2015</span>
          </li>

        <?php endforeach; ?>
      </ul>
    </div>

    <div class="comments">
      <div class="fb-comments" data-href="http://developers.facebook.com/docs/plugins/comments/" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
    </div>
  </div>
</article>