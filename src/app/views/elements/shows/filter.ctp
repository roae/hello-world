<?php
$CitySelected = Configure::read("CitySelected");
if(!empty($CitySelected)){
	if(!isset($dates)){
		$dates = $this->requestAction(
			"/shows/get_date/".(isset($movie_id)? $movie_id : null),
			array('locationsSelected'=>array_keys(Configure::read("LocationsSelected")))
		);
	}
	$day = "";
	$today = date("Y-m-d");
	#pr($dates);
	foreach($dates as $key => $date){
		if($date == $today){
			$dates[$today] = $this->Time->format("[:D:] d [:M:]",$today)." ([:hoy:])";
		}else if($date == date("Y-m-d",strtotime("+1 day"))){
			$dates[$date] = $this->Time->format("[:D:] d [:M:]",$date)." ([:manana:])";
		}else if($date != $today){
			$dates[$date] = $this->Time->format("[:D:] d [:M:]",$date);
		}
		unset($dates[$key]);
	}
	if(empty($dates)){
		$dates[$today] = $this->Time->format("[:D:] d [:M:]",$today)." ([:hoy:])";
	}

	#pr($dates);

	$locations = Configure::read("LocationsList");
	if(!isset($this->data['Filter']['city']) && empty($this->data['Filter']['city'])){
		$this->data['Filter']['city'] = $this->Html->url(array('controller'=>'shows','action'=>'index','slug'=>$CitySelected['slug']));
	}
	#pr($this->data);
}
$citiesList = Configure::read("CitiesList");
foreach($citiesList as $city){
	$cities[$this->Html->url(array(
		'controller'=>'shows',
		'action'=>'index',
		'slug'=>Inflector::slug( low( $city ), '-' )
	))] = $city;
}

//pr($this->data);
?>
<?php echo $this->Ajax->div("FilterBillboard",array('class'=>'filter'));$this->I18n->start();?>
<?= $this->Form->create("Filter",array('url'=>$this->Html->url(),'id'=>'BillboardFilter')); ?>
	<div class="filter-select">
		<?= !empty($CitySelected)?$this->Form->input("date",array('type'=>'select','options'=>$dates,'label'=>"[:day-to-show:]")): "";?>
		<?= $this->Form->input("city",array(
			'type'=>'select',
			'options'=>$cities,
			'label'=>"[:city-to-show:]",
			'value'=>isset($this->data['Filter']['city']) ? $this->data['Filter']['city'] : "",
			'div'=>array('class'=>empty($CitySelected)? "input select center":"input select"),
			'empty'=>empty($CitySelected)? "Selecciona tu ciudad" : null,
		));?>
	</div>
<?php if(!empty($CitySelected)){ ?>
	<div class="filter-complex">
		<span class="label">[:select-complex:]</span>
		<?= $this->Form->input("Location",array('label'=>false,'options'=>$locations,'multiple'=>'checkbox')); ?>
	</div>
<?php } ?>
<?= $this->Form->end(); ?>
<?php echo $this->I18n->end().$this->Ajax->divEnd("FilterBillboard"); ?>