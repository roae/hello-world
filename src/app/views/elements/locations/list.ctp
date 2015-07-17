<?php
/* @var $this View */
$records = $this->requestAction(array(
	'controller'=>'locations',
	'action'=>'get',
	'type'=>'all',
	'query'=>array(
		'conditions'=>array('Location.status'=>1,'Location.trash'=>0),
		'contain'=>array(
			'City',
		)
	)
));
#pr($records);
if(!empty($records)):
?>
	<h2>[:contacts-locations:]</h2>
	<ul class="locationsList">
		<?php foreach($records as $record): ?>
			<li>
				<h3><?= h($record['Location']['name']) ?></h3>
				<span>[:direccion:]</span>
				<span class="address"><?= $record['Location']['street']." #".$record['Location']['outside'].(!empty($record['Location']['interior'])? " - ".$record['Location']['interior'] : "")." ".$record['Location']['neighborhood']." C.P. ".$record['Location']['zip'].", ".$record['City']['name'].", ".$record['Location']['state'] ?></span>
				<span>[:telefonos:]</span>
				<span class="phones"><?= $record['Location']['phone_numbers']; ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>