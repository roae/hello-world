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
				'fields'=>array('Movie.id','Movie.title', 'Movie.genre', 'Movie.duration','Movie.synopsis'),
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

	<?php foreach($billboard as $show) { ?>

		<div class="movie">
			<div class="image-container">
				<?= $this->Html->image($this->Uploader->generatePath($show['Poster'],'medium'));?>
				<div class="details link">
					<strong class="title"><?= $show['Movie']['title'] ?></strong>
					<div class="sinopsis">
						<?= $show['Movie']['synopsis']?>
					</div>
					<?= $this->Html->link("[:ver_detalles:]", array("controller" => "movies", "action" => "view", "id" => $show["Movie"]["id"], "slug" => Inflector::slug($show["Movie"]["title"], "-")), array("class" => "watch-trailer fwd")) ?>
					<a class="buy-tickets" href="">Horarios</a>
				</div>
			</div>

			<div class="info">
				<h2>
					<?= $this->Html->link($show['Movie']['title'], array("controller" => "movies", "action" => "view", "id" => $show["Movie"]["id"], "slug" => Inflector::slug($show["Movie"]["title"], "-"))) ?>
				</h2>
				<span class="duration"><?= ($show['Movie']['duration'] != '') ? $show['Movie']['duration'].' mins' : '' ?></span>
				<span class="genre"><?= $show['Movie']['genre'] ?></span>
			</div>
		</div>

	<?php } ?>

<?php
	if(Configure::read("LocationSelected.id")) {
		echo $this->Html->link("Ver cartelera completa",array('controller'=>'shows','action'=>'index','slug'=>Inflector::slug(Configure::read("LocationSelected.name"),"-")));
	}
?>