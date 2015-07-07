<?php
$articles = $this->requestAction( array(
	'controller' => 'articles',
	'action' => 'get',
	'type' => 'all',
	'query' => array(
		'fields' => array( 'Article.id', 'Article.titulo', 'Article.contenido', 'Article.slug' ),
		'conditions' => array(
			'Article.trash' => 0,
			'Article.status' => 1,
		),
		'limit' => 2,
		'order' => 'Article.id DESC'
	)
) );
foreach( $articles as $key => $record ){
	$url = array( 'controller' => 'articles','action' => 'view','id' => $record['Article']['id'],'slug' => $record['Article']['slug']);
	?>
	<article>
		<h3><?= $this->Html->link($record['Article']['titulo'],$url) ?></h3>
		<?= $this->Text->truncate( $record['Article']['contenido'], 100 ,array('html'=>true)); ?>
		<?= $this->Html->link( '[:continuar-leyendo:]', $url, array( 'class' => 'keep-reading' ) ) ?>
	</article>

<?php } ?>
