<?php
	/* @var $this View */
	$restParams = array();
	if(isset($LocationsSelected) && !empty($LocationsSelected)){
		$restParams = array('named'=>array('locations'=>implode("-",array_keys($LocationsSelected))));
	}

	$billboard = $this->requestAction(am(array('controller'=>'shows','action'=>'rest'),$restParams));
	#pr($billboard);
?>
<ul class="movies-list">
	<?php foreach($billboard as $show) {
	?>

		<li class="movie">

			<div class="image-container">
				<?= $this->Html->image($this->Uploader->generatePath($show['Poster'],'medium'));?>
				<?php
				$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
				$labels = "";
				$presale = false;
				#Etiqueta preventa
				if(isset($show['Movie']['presale']) && $show['Movie']['presale']){
					$presale_start = $this->Time->gmt($show['Movie']['presale_start']);
					$presale_end = $this->Time->gmt($show['Movie']['presale_end']);

					if($today >= $presale_start && $today <= $presale_end){
						$presale = true;
						$labels.= $this->Html->tag("li","[:presale:]",'presale');
					}
				}
				#Etiqueta Estreno
				if(isset($show['Movie']['premiere_end']) && $show['Movie']['premiere_end']){
					$premiere_start = $this->Time->gmt($this->Time->format("Y-m-d",$show['Show']['schedule']));
					$premiere_end = $this->Time->gmt($show['Movie']['premiere_end']);

					if($today >= $premiere_start && $today < $premiere_end){
						$labels.= $this->Html->tag("li",'[:premiere:]','premiere');
					}
				}
				if($labels){
					echo $this->Html->tag("ul",$labels,"labels");
				}
				?>
				<div class="details link">
					<strong class="title"><?= $show['Movie']['title'] ?></strong>
					<div class="sinopsis">
						<?= $show['Movie']['synopsis']?>
					</div>
					<?php
					$style="";
					if(trim($show['Movie']['trailer'])){
						echo $this->Html->link("[:ver_trailer:]", array("controller" => "movies", "action" => "view", "slug" => $show["Movie"]["slug"],"#"=>"trailer"), array("class" => "watch-trailer"));
						$style="display:none;";
					}

					echo $this->Html->link("[:ver_detalles:]", array("controller" => "movies", "action" => "view", "slug" => $show["Movie"]["slug"]), array("class" => "watch-trailer fwd",'style'=>$style));

					$schedules_url = '#';
					$schedules_slug_data = $show['Movie']['slug'];

					if( isset($CitySelected['name']) && $CitySelected['name'] ) {
						$schedules_slug_data = '';
						$schedules_url = array('controller'=>'shows','action'=>'index','slug'=>Inflector::slug(low($CitySelected['name']),'-'),'#' => $show['Movie']['slug']);
					}
					if($presale){

						echo $this->Html->link("[:ver_preventa:]", array("controller" => "movies", "action" => "view", "slug" => $show["Movie"]["slug"],'#'=>'horarios'),array('class'=>'buy-tickets', 'data-slug' => $schedules_slug_data));
					}else{
						echo $this->Html->link("[:ver_horarios:]", $schedules_url,array('class'=>'buy-tickets', 'data-slug' => $schedules_slug_data));
					}

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