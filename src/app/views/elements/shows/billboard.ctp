<?php
	/* @var $this View */
	#$conditions = $movieLocationConditions = array();
	$restParams = array();
	if(isset($LocationsSelected) && !empty($LocationsSelected)){
		#$conditions = array('Show.location_id'=>array_keys($LocationsSelected));
		#$movieLocationConditions =array('MovieLocation.location_id'=>array_keys($LocationsSelected));
		$restParams = array('named'=>array('locations'=>implode("-",array_keys($LocationsSelected))));
	}
	/*$query = array(
		'fields'=>array('Show.id'),
		'contain'=>array(
			'Movie'=>array(
				'fields'=>array('Movie.id','Movie.title', 'Movie.genre', 'Movie.duration','Movie.synopsis','Movie.slug'),
				'Poster',
				'MovieLocation'=>array(
					'conditions'=>$movieLocationConditions,
					'limit'=>1
				),
			)
		),
		'group'=>array(
			'movie_id'
		),
		'conditions'=>$conditions
	);*/

	$billboard = $this->requestAction(am(array('controller'=>'shows','action'=>'rest'),$restParams));
	//pr($billboard);
?>
<ul class="movies-list">
	<?php foreach($billboard as $show) {
	?>

		<li class="movie">

			<div class="image-container">
				<?= $this->Html->image($this->Uploader->generatePath($show['Poster'],'medium'));?>
				<?php
				if(isset($show['Movie']['presale']) && $show['Movie']['presale']){
					list($start_year,$start_month,$start_day) = explode("-",$show['Movie']['presale_start']);
					list($end_year,$end_month,$end_day) = explode("-",$show['Movie']['presale_end']);
					$presale_start = mktime(0,0,0,$start_month,$start_day,$start_year);
					$presale_end = mktime(0,0,0,$end_month,$end_day,$end_year);
					$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
					if($today >= $presale_start && $today <= $presale_end){
						echo $this->Html->tag("span","[:presale:]",'presale');
					}
				}
				?>
				<div class="details link">
					<strong class="title"><?= $show['Movie']['title'] ?></strong>
					<div class="sinopsis">
						<?= $show['Movie']['synopsis']?>
					</div>
					<?
						echo $this->Html->link("[:ver_detalles:]", array("controller" => "movies", "action" => "view", "slug" => $show["Movie"]["slug"]), array("class" => "watch-trailer fwd"));

						$schedules_url = '#';
						$schedules_slug_data = $show['Movie']['slug'];

						if( isset($CitySelected['name']) && $CitySelected['name'] ) {
							$schedules_slug_data = '';
							$schedules_url = array('controller'=>'shows','action'=>'index','slug'=>Inflector::slug(low($CitySelected['name']),'-'),'#' => $show['Movie']['slug']);
						}

						echo $this->Html->link("[:ver_horarios:]", $schedules_url,array('class'=>'buy-tickets', 'data-slug' => $schedules_slug_data));
					?>
				</div>
			</div>

			<div class="info">
				<h2>
					<?= $this->Html->link($show['Movie']['title'], array("controller" => "movies", "action" => "view", "slug" => $show["Movie"]["slug"])) ?>
				</h2>
				<span class="duration"><?= ($show['Movie']['duration'] != '') ? $show['Movie']['duration'].' mins' : '' ?></span>
				<span class="genre"><?= $show['Movie']['genre'] ?></span>
			</div>

		</li>

	<?php } ?>
</ul>
<?php

	if(Configure::read("LocationSelected.id")) {
		echo $this->Html->link("Ver cartelera completa",array('controller'=>'shows','action'=>'index','slug'=>Inflector::slug(Configure::read("LocationSelected.name"),"-")));
	}
?>