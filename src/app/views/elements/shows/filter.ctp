<?php
$dates = $this->requestAction(array(
	'controller'=>'shows',
	'action'=>'get_date'
));
$day = "";
foreach($dates as $key => $date){
	if($date == date("Y-m-d")){
		$dates[$date] = $this->Time->format("[:D:] d [:F:]",$date)." ([:hoy:])";
	}else if($date == date("Y-m-d",strtotime("+1 day"))){
		$dates[$date] = $this->Time->format("[:D:] d [:F:]",$date)." ([:manana:])";
	}else{
		$dates[$date] = $this->Time->format("[:D:] d [:F:]",$date);
	}
	unset($dates[$key]);
}
#pr($dates);
$cities = Configure::read("CitiesList");
$locations = Configure::read("LocationsList");
#pr($locations);
$this->data['Filter']['date']= $this->Session->read("BillboardFilter.date");
$this->data['Filter']['city']= $CitySelected['id'];
?>

<div class="filter">
	<?= $this->Form->create("Filter",array('url'=>array('controller'=>'shows','action'=>'set_filter'),'id'=>'BillboardFilter')); ?>
	<div class="input">
		<label class="label">[:day-to-show:]</label>
		<div class="filter-select">
			<?= $this->Form->input("date",array('type'=>'select','options'=>$dates,'label'=>""));?>
		</div>
	</div>

	<!--<span class="label">Ciudad</span>
	<div class="filter-select">
		<?= $this->Form->input("city",array('type'=>'select','options'=>$cities,'label'=>""));?>
	</div>-->

	<span class="label">[:select-complex:]</span>
	<?= $this->Form->input("location",array('label'=>false,'options'=>$locations,'multiple'=>'checkbox')); ?>
	<?= $this->Form->end(); ?>
</div>