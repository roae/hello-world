<?php /*@var $this View*/
$record = $this->requestAction(array(
	'controller'=>'ads',
	'action'=>'get',
	'type'=>'first',
	'query'=>array(
		'conditions'=>array(
			'Ad.type'=>$type,
			'Ad.status'=>1,
			'Ad.trash'=>0,
		),
		'contain'=>array(
			'AdsGroup'=>array(
				'AdsGroup.status'=>1,
				'AdsGroup.trash'=>0,
			),
			Configure::read("AdTypes.$type")
		)
	)
));
//pr($record);
$img =  $this->Html->image($record[Configure::read("AdTypes.$type")]['url'],array('alt'=>$record['Ad']['title']));
echo $record['Ad']['link'] ? $this->Html->link($img,$record['Ad']['link'],array('escape'=>false)) : $img ;
?>
