<?php
/* @var $this View */
$this->Html->script(array(
	'ext/perfect-scrollbar.jquery.min.js'
),array('inline'=>false));
$conditions = array();
if(isset($LocationsSelected) && !empty($LocationsSelected)){
	$conditions = array('MovieLocation.location_id'=>array_keys($LocationsSelected));
}

$premiere = $this->requestAction(array('controller'=>'movies','action'=>'premiere'));
if(!empty($premiere)){
	$_premieres = array();
	foreach( $premiere as $index => $item ){
		if($item['MovieLocation']['premiere_date'] == "0000-00-00"){
			$_premieres[] = $item;
			unset($premiere[$index]);
		}
	}
	foreach($_premieres as $item){
		$premiere[] = $item;
	}

	?>
<section class="next-premieres">

	<header class="col-container">
		<h2 class="titleCommingSoon">[:proximamente-en-cartelera:]</h2>
	</header>

	<div class="movies">
		<div class="antiscroll-wrap">
			<div class="movies-list box">
				<div class="antiscroll-inner">
					<div class="box-inner">
						<?php foreach($premiere as $item) { ?>
							<div class="movie link">
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
										<?= $this->Html->link($item['Movie']['title'], array("controller" => "movies", "action" => "view", "slug" => $item["Movie"]["slug"]),array('class'=>'fwd')) ?>
									</h2>
									<?php
									if($item['MovieLocation']['premiere_date'] != "0000-00-00"){
									?>
										<span class="date"><?= $this->Time->format("d / [:M:] / Y",$item['MovieLocation']['premiere_date']);?></span>
									<?php
									}
									?>


								</div>

							</div>

						<?php } ?>
					</div>
				</div>
			</div>
		<!--</div>-->
	</div>
</section>
<?
}
?>
