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

	<?php foreach($billboard as $show) { ?>

		<li class="movie link">
			<div class="image-container">
				<div class="sinopsis">
					<strong><?= $show['Movie']['title'] ?></strong>

					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a risus consectetur, bibendum orci sit amet, 	ultrices enim. Aliquam erat volutpat.
					</p>

					<a class="watch-trailer" href="">Ver trailer</a>
					<a class="buy-tickets" href="">Comprar boletos</a>
				</div>

				<?= $this->Html->image($this->Uploader->generatePath($show['Poster'],'medium'));?>
			</div>

			<div class="info">
				<header>
					<h2>
						<a class="fwd" href=""><?= $show['Movie']['title'] ?></a>
					</h2>
				</header>
			</div>
		</li>

	<?php } ?>

<?php
	if(Configure::read("LocationSelected.id")) {
		echo $this->Html->link("Ver cartelera completa",array('controller'=>'shows','action'=>'index','slug'=>Inflector::slug(Configure::read("LocationSelected.name"),"-")));
	}
?>