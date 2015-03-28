<?php
/* @var $this View */
$conditions = array();
if(isset($LocationsSelected) && !empty($LocationsSelected)){
	$conditions = array('MovieLocation.location_id'=>array_keys($LocationsSelected));
}

$premiere = $this->requestAction(array('controller'=>'movies','action'=>'premiere'));
#pr($premiere);
?>
<section class="next-premieres">

	<header class="col-container">
		<h2 class="titleCommingSoon">[:proximamente-en-cartelera:]</h2>
	</header>

	<div class="movies">
		<div class="movies-list owl-carousel">


			<?php foreach($premiere as $item) { ?>

				<div class="movie">
					<div class="image-container">
						<?= $this->Html->image($this->Uploader->generatePath($item['Poster'],'medium'));?>
						<div class="details link">
							<strong class="title"><?= $item['Movie']['title'] ?></strong>
							<div class="sinopsis">
								<?= $item['Movie']['synopsis']?>
							</div>
							<?
							echo $this->Html->link("[:ver_detalles:]", array("controller" => "movies", "action" => "view", "slug" => $item["Movie"]["slug"]), array("class" => "watch-trailer fwd"));

							$schedules_url = '#';
							$schedules_slug_data = $item['Movie']['slug'];

							if( isset($CitySelected['name']) && $CitySelected['name'] ) {
								$schedules_slug_data = '';
								$schedules_url = array('controller'=>'shows','action'=>'index','slug'=>Inflector::slug(low($CitySelected['name']),'-'),'#' => $item['Movie']['slug']);
							}

							echo $this->Html->link("[:ver_horarios:]", $schedules_url,array('class'=>'buy-tickets', 'data-slug' => $schedules_slug_data));
							?>
						</div>
					</div>

					<div class="info">
						<h2>
							<?= $this->Html->link($item['Movie']['title'], array("controller" => "movies", "action" => "view", "slug" => $item["Movie"]["slug"])) ?>
						</h2>
						<span class="duration"><?= ($item['Movie']['duration'] != '') ? $item['Movie']['duration'].' mins' : '' ?></span>
						<span class="genre"><?= $item['Movie']['genre'] ?></span>
					</div>
				</div>

			<?php } ?>

			<?php
			if(Configure::read("LocationSelected.id")) {
				echo $this->Html->link("Ver cartelera completa",array('controller'=>'shows','action'=>'index','slug'=>Inflector::slug(Configure::read("LocationSelected.name"),"-")));
			}
			?>
		</div>
	</div>
</section>