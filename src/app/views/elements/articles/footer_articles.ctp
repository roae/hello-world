<?php
  $articles = $this->requestAction(array(
    'controller'=>'articles',
    'action'=>'get',
    'type'=>'list',
    'query'=>array(
      'fields' => array('Article.id', 'Article.titulo', 'Article.contenido', 'Article.slug'),
      'conditions'=>array(
        'Article.trash'=>0,
        'Article.status'=>1,
      ),
      'limit' => 2
    )
  ));

  foreach ($articles as $key => $article) {
?>

  <article>
    <header>
      <h3><?= $article ?></h3>
    </header>

    <p>
      Cuando Anastasia "Ana" Steele, una estudiante de Literatura de la Universidad de Washington ...
    </p>

    <?= $this->Html->link('Continuar leyendo', array('controller' => 'articles', 'action' => 'view', 'id' => $key, 'slug' => $article), array('class' => 'keep-reading')) ?>
  </article>

<?php } ?>
