<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_services_trash:]');
echo $this->Ajax->div("data",array('class'=>'pagination'));
if(!empty($recordset)){
	$this->Paginator->options(array('rev' => '#data','url' => am(array('controller' => 'services','action' => 'index','admin' => 1),$this->params['named'])));
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
			$this->Html->tag('button',$this->Html->tag("i","","icon-remove-sign")."<span>[:clean_trash:]</span>",array('type'=>'submit','name'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'destroy')),'onclick'=>"return paginAction(this,'[:warning_message_delete_multiple_services:]')",'class'=>'btn btn_danger'))
			,array('class'=>'rTools'))
		,array('class'=>'tools floating')
	);
	$th = array(
		$this->Form->checkbox("Xpagin.all",array('class'=>'checkAll','id'=>'')),
		$this->Paginator->sort('<span>[:Service_name:]</span><span class="sortind"></span>','Service.name',array('title'=>'[:sort_by:] [:Service_name:]','escape'=>false)),
		'[:Service_icon:]',
		$this->Paginator->sort('<span>[:Service_created:]</span><span class="sortind"></span>','Service.created',array('title'=>'[:sort_by:] [:Service_created:]','escape'=>false)),
		$this->Paginator->sort('<span>ID</span><span class="sortind"></span>','Service.id',array('title'=>'[:sort_by:] [:Service_id:]','escape'=>false)),
		'[:actions:]'
	);
	$tr=array();
	foreach((array)$recordset as $count=>$record){
		$actions=$this->Html->div('btn-group',
			$this->Paginator->link($this->Html->tag("i","","icon-ok-sign")." [:restore:]",array('action'=>'restore',$record['Service']['id']),array('class'=>'action btn btn_primary noHistory','escape'=>false)).
			$this->Paginator->link($this->Html->tag("i","","icon-remove-sign"),array('action'=>'destroy',$record['Service']['id']),array('class'=>'action btn btn_danger','rel'=>'[:delete_city_name:]: '.h($record['Service']['name']).'?','escape'=>false,'title'=>'[:destroy:]')
				,array('class'=>"dropdown-menu",'role'=>'menu'))
		);
		$icon = (isset($record['Icon']['url'])) ? $this->Html->image($record['Icon']['url']) : "";
		$tr[]=array(
			$this->Form->checkbox("Xpagin.record][",array('class'=>'check','id'=>'','value'=>$record['Service']['id'])),
			$this->Paginator->link($record['Service']['name'],array('action' => 'view',$record['Service']['id']),array('rev'=>'','escape'=>false,'class'=>'highlight','name'=>'[:edit:] '.$record['Service']['name'])),
			$icon,
			$this->Html->tag("span",$this->Time->format('d-[:M:]-Y',$record['Service']['created']),array('title'=>$this->Time->format('[:F:] d, Y  h:m a',$record['Service']['created']))),
			#$this->Time->format('d/m/Y h:m a',$record['Service']['modified']),
			array($record['Service']['id'],array('class'=>'center')),
			$actions
		);
	}
	echo $this->Html->tag("table",
		$this->Html->tag("colgroup",
			$this->Html->tag("col",null,array('span'=>1,'width'=>'15px')).
			$this->Html->tag("col",null,array('span'=>1)).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'45px')).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'140px')).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'30px')).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'150px'))
		).$this->Html->tag("thead",$this->Html->tableHeaders($th),array('class'=>'floating')).$this->Html->tag("tbody",$this->Html->tableCells($tr,array('class'=>'odd'),array('class'=>'even'))).$this->Html->tag("tfoot",$this->Html->tableHeaders($th)),array('class'=>'grid','cellspacing'=>'0','border'=>0)
	);

	echo $this->element("pagination-control-bar");
	$this->I18n->end();

}else if(isset($this->data['Xpagin']['search'])){
	echo $this->Html->div("noRecords",
		$this->Html->tag("i","","icon-search icon").
		$this->Html->tag("div","[:System.services_not_found_trash:] ".$this->Html->tag("strong",h($this->data['Xpagin']['search']))).
		$this->Form->create("Xpagin",array('url'=>$this->Paginator->url(),'id'=>'XpaginForm','class'=>'clearfix')).
		$this->Html->tag("div",
			$this->Form->input("search",array('placeholder'=>'[:search:]','label'=>false)).
			$this->Form->hidden('url',array('value'=>'','id'=>'XpaginUrl')). #necesario para las ejecuciones ajax
			$this->Form->button('<i class="icon-search"></i>',array('type'=>'submit','class'=>'btn'))
			,'searchForm span4 offset4').
		$this->Form->end().
		$this->Html->link("[:back_to_list:]",array('action'=>'index'),array('class'=>'btn btn_primary'))
	);
}else{
	echo $this->Html->div("noRecords",
		$this->Html->tag("i","","icon-trash icon").
		$this->Html->tag("div","[:System.no_services_yet_in_trash:]").
		$this->Html->link($this->Html->tag("i","","icon-plus")."[:go_city_list:]",array('action'=>'index'),array('class'=>'btn btn_success','escape'=>false))
	);
}
echo $this->Ajax->divEnd("data");
?>