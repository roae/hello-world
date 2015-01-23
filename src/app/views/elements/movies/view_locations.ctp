<?php
/* @var $this View */
?>

<ul class="viewMovieLocations">
	<?
	foreach($locations as $record){
	?>
		<li>
			<span class="location">- <?= $record['Location']['name']?></span>
			<span class="premiere">[:MovieLocation-premiere_end:] <?= $this->Time->format("d [:\of:] [:F:], Y",$record['premiere_end'])?></span>
			<?= $record['comming_soon']? "<span class='comming_soon'>[:mostrandose-en-proximos-estrenos:]</span>" : ""?>
			<?= $record['presale']? "<span class='presale'>[:preventa-active:] ".$this->Time->format("d [:\of:] [:F:], Y")." [:to:] ".$this->Time->format("d [:\of:] [:F:], Y")."</span>":"" ?>
		</li>
	<?
	}
	?>
</ul>
