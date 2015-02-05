<?= $this->element("locations/select");?>
<h1>Cartelera: <?= Configure::read("LocationSelected.name")?></h1>
<?php
foreach($billboard as $item){
?>
	<div class="movie" style="overflow: hidden;">
		<?= $this->Html->image($this->Uploader->generatePath($item['Movie']['Poster'],'medium'),array('style'=>'float: left;margin: 10px;'));?>
		<?= $this->Html->tag("h2",$item['Movie']['title'])?>
		<div class="schedule">
			<?php
			foreach($item['Show'] as $type=>$shows):
				echo $this->Html->tag("h4",Inflector::humanize($type));
				$schedule = array();
				foreach($shows as $show){
					$schedule[]= $this->Time->format("H:i",$show['schedule']);
				}
				echo $this->Html->tag("div",implode(" | ",$schedule));
			endforeach;

			?>
		</div>
	</div>
<?php
}
?>