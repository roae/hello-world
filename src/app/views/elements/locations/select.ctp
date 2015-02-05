<?php
/* @var $this View */
$locations=$this->requestAction(array(
	'controller'=>'locations',
	'action'=>'get',
	'type'=>'list',
	'query'=>array(
		'conditions'=>array(
			'Location.trash'=>0,
			'Location.status'=>1,
		)
	)
));

echo $this->Form->create("Location",array('action'=>'set_location'));
	echo $this->Form->input("id",array('options'=>$locations,'label'=>false,'empty'=>'Selecciona un complejo','div'=>false,'value'=>Configure::read("LocationSelected.id")));
	echo $this->Form->button("seleccionar");
echo $this->Form->end();
?>