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
			'Term.class'=>'Category'
		),
		'group'=>'Term.id',
	)
));
?>

<div class="panel" id="categories">
	<div class="h3">
		[:categories_list:]
	</div>
	<ul>
		<?php
		foreach($terms as $record){
			echo $this->Html->tag("li",
				$this->Html->link(
					$record['Term']['nombre'].$this->Html->tag("span",$record['Term']['cantidad'],"cantidad"),
					array('controller'=>'articles','action'=>'index','category_slug'=>$record['Term']['slug']),
					array('escape'=>false)
				),
			'category');
		}
		?>
	</ul>
</div>