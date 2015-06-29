<?php
if(!empty($CitySelected)){

	/*$dates = $this->requestAction(array(
		'controller'=>'shows',
		'action'=>'get_date',
		(isset($movie_id)? $movie_id : null)
	));*/
	if(!isset($dates)){
		$dates = $this->requestAction("/shows/get_date/".(isset($movie_id)? $movie_id : null));
	}

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

	$locations = Configure::read("LocationsList");
	$this->data['Filter']['city'] = $this->Html->url(array('controller'=>'shows','action'=>'index','slug'=>$CitySelected['slug']));
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