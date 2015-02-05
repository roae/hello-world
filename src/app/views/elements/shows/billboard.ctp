<?php
/* @var $this View */
#$locationSelected = Configure::read("LocationSelected");
$conditions = array();
if(Configure::read("LocationSelected.id")){
	$conditions = array('Show.location_id'=>Configure::read("LocationSelected.id"));
}
$query = array(
	'fields'=>array('Show.id'),
	'contain'=>array(
		'Movie'=>array(
			'fields'=>array('Movie.id','Movie.title'),
			'Poster'
		)
	),
	'group'=>array(
		'movie_id'
	),
	'conditions'=>$conditions
);
$billboard = $this->requestAction(array('controller'=>'shows','action'=>'get','type'=>'all','query'=>$query));
//pr($billboard);
?>
<div class="movies " style="overflow: hidden;">
	<h1>Cartelera: <?= Configure::read("LocationSelected.name")?></h1>

	<?php
	foreach($billboard as $show){
		?>
		<div class="movie" style="float: left; margin: 10px;">
			<?= $this->Html->image($this->Uploader->generatePath($show['Poster'],'medium'));?>
		</div>
	<?php
	}

	?>
</div>
<?php
if(Configure::read("LocationSelected.id")){
	echo $this->Html->link("Ver cartelera completa",array('controller'=>'shows','action'=>'index','slug'=>Inflector::slug(Configure::read("LocationSelected.name"),"-")));
}
?>