<?php
/* @var $this View */
$conditions = array();
if(isset($LocationsSelected) && !empty($LocationsSelected)){
	$conditions = array('MovieLocation.location_id'=>array_keys($LocationsSelected));
}

$premiere = $this->requestAction(array('controller'=>'movies','action'=>'premiere'));
#pr($premiere);
if(!empty($premiere)){
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
						<?php
						if(isset($item['MovieLocation']['presale']) && $item['MovieLocation']['presale']){
							echo $this->Html->tag("span","[:presale:]",'presale');
						}
						?>

					</div>

					<div class="info">
						<h2>
							<?= $this->Html->link($item['Movie']['title'], array("controller" => "movies", "action" => "view", "slug" => $item["Movie"]["slug"])) ?>
						</h2>
						<span class="date"><?= $this->Time->format("d / [:M:] / Y",$item['MovieLocation']['premiere_date']);?></span>

					</div>

				</div>

			<?php } ?>


		</div>
	</div>
</section>
<?
}
?>
