<?php
if(!isset($space)){$space="";}
if(!isset($class)){$class="";}
foreach( (array) $recordset as $record){
	$actions=$this->Html->div('btn-group',
		$this->Paginator->link("<i class='icon-pencil'></i>",array('action' => 'edit',$record['Term']['id']),array('class'=>'action btn_primary noHistory','title'=>'[:edit:] '.$record['Term']['nombre'],'escape'=>false,'rev'=>'#ajaxForm')).
		$this->Paginator->link("<i class='icon-trash'></i>",array('action'=>'delete',$record['Term']['id']),array('class'=>'action btn_danger','rel'=>'[:delete_tag_confirm:]: '.h($record['Term']['nombre']).'?','escape'=>false))
	);
	$tr=array(
		$this->Form->checkbox("Xpagin.record][",array('class'=>'check','id'=>'','checked'=>'','value'=>$record['Term']['id'])),
		$this->Paginator->link($space." ".h($record['Term']['nombre']),array('action' => 'edit',$record['Term']['id']),array('class'=>'highlight action noHistory','rev'=>'#ajaxForm','title'=>'[:edit:] '.$record['Term']['nombre'],'escape'=>false)),
		$record['Term']['descripcion'],
		$record['Term']['slug'],
		array($record['Term']['cantidad'],array('class'=>'center')),
		$actions,
	);

	echo $this->Html->tableCells($tr,array('class'=>'odd '),array('class'=>'even '));
	if(!empty($record['children'])){
		echo $this->element("terms/tree",array('recordset'=>$record['children'],'space'=>$space."&#8212;",'class'=>$class." parent__".$record['Term']['id']));
	}
}
?>
