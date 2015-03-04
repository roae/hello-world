<div id="articles-container">
  <div class="top-message">
    <div class="col-container">
      <h1>Blog, noticias recientes</h1>
      <p>
        Bienvenido a nuestro blog, lee las ultimas noticias en el mundo del cine y actualizaciones en nuestro sitio.
      </p>
    </div>
  </div>

  <ul class="the-articles">

    <?php foreach ($recordset as $record): ?>

      <?php
        $article = $record['Article'];
        $tags = $record['Tag'];
        $categories = $record['Category'];
        $photo = $record['Foto'];
      ?>

      <li class="<?= (isset($photo['id'])) ? 'with-image': '' ?>" style="<?= (isset($photo['id'])) ? 'background-image: url('.$photo['big'].')': '' ?>">
        <div class="col-container">
          <article>
            <small class="date">
              Creado: 22 Enero, 2015 | en

              <?php
                $total_categories = count($categories);
                $_categories = array();

                foreach ($categories as $i => $category):
                  $_categories[] = $this->Html->link($category['nombre'], array('controller' => 'articles', 'action' => 'index', 'category_slug' => $category['slug']));
                endforeach;

                echo implode(', ', $_categories);
              ?>
            </small>

            <header>
              <h2><?= $article['titulo'] ?></h2>
            </header>

            <?= $this->Text->truncate($article['contenido'], 300, array('html' => true, 'exact' => false)) ?>

            <div class="bottom-article">
              <div class="tags">
                <ul>

                  <?php foreach ($tags as $tag): ?>

                    <li>
                      <?= $this->Html->link($tag['nombre'], array('controller' => 'articles', 'action' => 'index', 'tag_slug' => $tag['slug']), array('class' => 'tag')) ?>
                    </li>

                  <?php endforeach; ?>

                </ul>
              </div>

              <?= $this->Html->link('[:seguir_leyendo:]', array('controller' => 'articles', 'action' => 'view', 'id' => $article['id'], 'slug' => $article['slug']), array('class' => 'see-more')); ?>
            </div>
          </article>
        </div>
      </li>

    <?php endforeach; ?>

  </ul>

  <div class="articles-pagination">
    <?= $this->Paginator->numbers(array('separator' => null)) ?>
  </div>

</div>
