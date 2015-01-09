<?php
/* @var $this View */
$terms = $this->requestAction(array(
	'controller'=>'terms',
	'action'=>'get',
	'type'=>'all',
	'query'=>array(
		'fields'=>array('Term.id','Term.nombre','Term.slug','Term.cantidad'),
		'joins'=>array(
			array(
				'type'=>'left',
				'table'=>'articles_terms',
				'alias'=>'Article',
				'conditions'=>array('Term.id = Article.term_id')
			),
		),
		'conditions'=>array(
			'Term.class'=>'Tag'
		),
		'group'=>'Term.id',
	)
));
?>

<div class="panel" id="tags">
	<div class="h3">
		[:tag_cloud:]
	</div>
	<?php
		foreach($terms as $record){
			echo $this->Html->link(
				$record['Term']['nombre'].$this->Html->tag("span",$record['Term']['cantidad'],'cantidad'),
				array('controller'=>'articles','action'=>'index','tag_slug'=>$record['Term']['slug']),
				array('class'=>'tag','escape'=>false)
			);
		}
	?>
</div>