<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_locations_list:]');
echo $this->Ajax->div("data",array('class'=>'pagination'));
if(!empty($recordset)){
	$this->Paginator->options(array('rev' => '#data','url' => am(array('controller' => 'locations','action' => 'index','admin' => 1),$this->params['named'])));
	echo $this->Form->create("Xpagin",array('url'=>$this->Paginator->url(),'id'=>'XpaginForm'));
	echo $this->Form->hidden('url',array('value'=>'','id'=>'XpaginUrl'));#necesario para las ejecuciones ajax
	$this->I18n->start();
	echo $this->Html->tag("div",
		$this->Html->tag("div",
			$this->Html->tag("div",
				$this->Form->input("search",array('placeholder'=>'[:search:]','label'=>false)).
				$this->Form->button('<i class="icon-search"></i>',array('type'=>'submit','class'=>'btn'))
				,'searchForm')
			,"lTools").
		$this->Html->tag("div",
			#$this->Html->tag('button',$this->Html->tag("i","","icon-circle")."<span>[:publish:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'status',1)),'onclick'=>'return paginAction(this,"[:message_confirm_publish_mutiple_locations:]")','class'=>'btn btn_primary')).
			#$this->Html->tag('button',$this->Html->tag("i","","icon-circle-blank")."<span>[:unpublish:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'status',0)),'onclick'=>'return paginAction(this,"[:message_confirm_unpublish_mutiple_locations:]")','class'=>'btn')).
			$this->Html->tag('button',$this->Html->tag("i","","icon-remove-sign")."<span>[:delete_location:]</span>",array('type'=>'submit','name'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'delete')),'onclick'=>"return paginAction(this,'[:warning_message_delete_multiple_locations:]')",'class'=>'btn btn_danger')).
			$this->Html->link($this->Html->tag("i","","icon-plus")."<span>[:add_location:]</span>",array('action'=>'add'),array('escape'=>false,'class'=>'btn btn_success'))
			,array('class'=>'rTools'))
		,array('class'=>'tools floating')
	);
	$th = array(
		$this->Form->checkbox("Xpagin.all",array('class'=>'checkAll','id'=>'')),
		$this->Paginator->sort('<span>[:Location_name:]</span><span class="sortind"></span>','Location.name',array('title'=>'[:sort_by:] [:Location_name:]','escape'=>false)),
		'<span>[:Location_venta_online:]</span>',
		'<span>[:Location_status:]</span>',
		$this->Paginator->sort('<span>[:Location_created:]</span><span class="sortind"></span>','Location.created',array('title'=>'[:sort_by:] [:Location_created:]','escape'=>false)),
		$this->Paginator->sort('<span>ID</span><span class="sortind"></span>','Location.id',array('title'=>'[:sort_by:] [:Location_id:]','escape'=>false)),
		'-'
	);
	$tr=array();
	foreach((array)$recordset as $count=>$record){
		$actions=$this->Html->div('btn-group',
			$this->Paginator->link($this->Html->tag("i","","icon-pencil")."[:editar:]",array('action'=>'edit',$record['Location']['id']),array('rev'=>'','class'=>'btn btn-info','escape'=>false)).
			$this->Form->button("<span class='caret'></span>",array('type'=>'button','class'=>'btn btn-info  dropdown-toggle','data-toggle'=>'dropdown')).
			$this->Html->tag("ul",
				$this->Html->tag("li",$this->Paginator->link("[:list_ads:]",array('controller'=>'locations','action'=>'index','location'=>$record['Location']['id']))).
				$this->Html->tag("li",$this->Paginator->link("[:list_promos:]",array('controller'=>'locations','action'=>'index','location'=>$record['Location']['id']))).
				$this->Html->tag("li",$this->Paginator->link("[:list_rooms:]",array('controller'=>'locations','action'=>'index','location'=>$record['Location']['id']))).
				$this->Html->tag("li",$this->Paginator->link("[:list_services:]",array('controller'=>'locations','action'=>'index','location'=>$record['Location']['id']))).
				$this->Html->tag("li","","divider").
				$this->Html->tag("li",
					$this->Paginator->link( $record['Location']['status']? $this->Html->tag("i","","icon-circle-blank")."[:unpublish:]" : $this->Html->tag("i","","icon-circle")."[:publish:]",array('action'=>'status',($record['Location']['status'])?0:1,$record['Location']['id']),array('class'=>'action '.($record['Location']['status'] ? "warning" : "success"),'rel'=>"[:change_status_Location_name:]: <span class='itemName'>".h($record['Location']['name']).'</span>?','escape'=>false))
				).
				$this->Html->tag("li",
					$this->Paginator->link( $record['Location']['venta_online']? $this->Html->tag("i","","icon-circle-blank")."[:venta_onlune_inactive:]" : $this->Html->tag("i","","icon-circle")."[:venta_online_active:]",array('action'=>'venta_online',($record['Location']['venta_online'])?0:1,$record['Location']['id']),array('class'=>'action '.($record['Location']['venta_online'] ? "warning" : "success"),'rel'=>"[:change_venta_online_Location_name:]: <span class='itemName'>".h($record['Location']['name']).'</span>?','escape'=>false))
				).
				$this->Html->tag("li","","divider").
				$this->Html->tag("li",$this->Paginator->link("[:smart_config:]",array('controller'=>'locations','action'=>'smart_config',$record['Location']['id']),array('rev'=>''))).
				$this->Html->tag("li","","divider").
				$this->Html->tag("li",$this->Paginator->link("<span class='icon-trash'></span> [:delete:]",array('action'=>'delete',$record['Location']['id']),array('class'=>'action danger','rel'=>'[:System.delete_location_name:]: '.h($record['Location']['name']).'?','escape'=>false)))
				,array('class'=>"dropdown-menu",'role'=>'menu'))
		);
		$tr[]=array(
			$this->Form->checkbox("Xpagin.record][",array('class'=>'check','id'=>'','value'=>$record['Location']['id'])),
			$this->Paginator->link($record['Location']['name'],array('action' => 'view',$record['Location']['id']),array('rev'=>'','escape'=>false,'class'=>'highlight','name'=>'[:edit:] '.$record['Location']['name'])),
			($record['Location']['venta_online'])? $this->Html->tag("span","[:activo:]","label label-success"):$this->Html->tag("span","[:inactivo:]","label label-warning"),
			($record['Location']['status'])? $this->Html->tag("span","[:si:]","label label-success"):$this->Html->tag("span","[:no:]","label label-warning"),
			$this->Html->tag("span",$this->Time->format('d-[:M:]-Y',$record['Location']['created']),array('title'=>$this->Time->format('[:F:] d, Y  h:m a',$record['Location']['created']))),
			#$this->Time->format('d/m/Y h:m a',$record['Location']['modified']),
			array($record['Location']['id'],array('class'=>'center')),
			$actions
		);
	}
	echo $this->Html->tag("table",
		$this->Html->tag("colgroup",
			$this->Html->tag("col",null,array('span'=>1,'width'=>'15px')).
			$this->Html->tag("col",null,array('span'=>1)).
			$this->Html->tag("col",null,array('span'=>3,'width'=>'140px')).
			#$this->Html->tag("col",null,array('span'=>1,'width'=>'140px')).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'30px')).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'150px'))
		).$this->Html->tag("thead",$this->Html->tableHeaders($th),array('class'=>'floating')).$this->Html->tag("tbody",$this->Html->tableCells($tr,array('class'=>'odd'),array('class'=>'even'))).$this->Html->tag("tfoot",$this->Html->tableHeaders($th)),array('class'=>'grid','cellspacing'=>'0','border'=>0)
	);

	echo $this->Html->tag("div",
		$this->Html->tag("div",
			$this->Html->tag("div",
				$this->Html->link($this->Html->tag("i","","icon-trash")."<span>[:trash_locations:]</span>",array('action'=>'trash'),array('class'=>'btn','escape'=>false))
				,array('class'=>'rTools'))
			,array('class'=>'tools'))
	);
	echo $this->element("pagination-control-bar");
	$this->I18n->end();

}else if(isset($this->data['Xpagin']['search'])){
	echo $this->Html->div("noRecords",
		$this->Html->tag("i","","icon-search icon").
		$this->Html->tag("div","[:System.locations_not_found:] ".$this->Html->tag("strong",h($this->data['Xpagin']['search']))).
		$this->Form->create("Xpagin",array('url'=>$this->Paginator->url(),'id'=>'XpaginForm','class'=>'clearfix')).
		$this->Form->hidden('url',array('value'=>'','id'=>'XpaginUrl')). #necesario para las ejecuciones ajax
		$this->Html->tag("div",
			$this->Form->input("search",array('placeholder'=>'[:search:]','label'=>false)).
			$this->Form->button('<i class="icon-search"></i>',array('type'=>'submit','class'=>'btn'))
			,'searchForm span4 offset4').
		$this->Form->end().
		$this->Html->link("[:System.back_to_list:]",array('action'=>'index'),array('class'=>'btn btn_primary'))
	);
}else{
	echo $this->Html->div("noRecords",
		$this->Html->tag("i","","icon-list-ul icon").
		$this->Html->tag("div","[:System.no_locations_yet:]").
		$this->Html->link($this->Html->tag("i","","icon-plus")."[:add_location:]",array('action'=>'add'),array('class'=>'btn btn_success','escape'=>false))
	);
}
echo $this->Ajax->divEnd("data");
?>