<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_ads_groups_trash:]');
echo $this->Ajax->div("data",array('class'=>'pagination'));
if(!empty($recordset)){
	$this->Paginator->options(array('rev' => '#data','url' => am(array('controller' => 'ads_groups','action' => 'index','admin' => 1),$this->params['named'])));
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
			$this->Html->tag('button',$this->Html->tag("i","","icon-remove-sign")."<span>[:clean_trash:]</span>",array('type'=>'submit','name'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'destroy')),'onclick'=>"return paginAction(this,'[:warning_message_delete_multiple_ads_groups:]')",'class'=>'btn btn_danger'))
			,array('class'=>'rTools'))
		,array('class'=>'tools floating')
	);
	$th = array(
		$this->Form->checkbox("Xpagin.all",array('class'=>'checkAll','id'=>'')),
		$this->Paginator->sort('<span>[:AdsGroup_name:]</span><span class="sortind"></span>','AdsGroup.name',array('title'=>'[:sort_by:] [:AdsGroup_name:]','escape'=>false)),
		$this->Paginator->sort('<span>[:AdsGroup_created:]</span><span class="sortind"></span>','AdsGroup.created',array('title'=>'[:sort_by:] [:AdsGroup_created:]','escape'=>false)),
		$this->Paginator->sort('<span>ID</span><span class="sortind"></span>','AdsGroup.id',array('title'=>'[:sort_by:] [:AdsGroup_id:]','escape'=>false)),
		'[:actions:]'
	);
	$tr=array();
	foreach((array)$recordset as $count=>$record){
		$actions=$this->Html->div('btn-group',
			$this->Paginator->link($this->Html->tag("i","","icon-ok-sign")." [:restore:]",array('action'=>'restore',$record['AdsGroup']['id']),array('class'=>'action btn btn_primary noHistory','escape'=>false)).
			$this->Paginator->link($this->Html->tag("i","","icon-remove-sign"),array('action'=>'destroy',$record['AdsGroup']['id']),array('class'=>'action btn btn_danger','rel'=>'[:delete_ads_group_name:]: '.h($record['AdsGroup']['name']).'?','escape'=>false,'title'=>'[:destroy:]')
			,array('class'=>"dropdown-menu",'role'=>'menu'))
		);
		$tr[]=array(
			$this->Form->checkbox("Xpagin.record][",array('class'=>'check','id'=>'','value'=>$record['AdsGroup']['id'])),
			$this->Paginator->link($record['AdsGroup']['name'],array('action' => 'view',$record['AdsGroup']['id']),array('rev'=>'','escape'=>false,'class'=>'highlight','name'=>'[:edit:] '.$record['AdsGroup']['name'])),
			#($record['AdsGroup']['status'])? $this->Html->tag("span","[:yes:]","label label-success"):$this->Html->tag("span","[:no:]","label label-warning"),
			$this->Html->tag("span",$this->Time->format('d-[:M:]-Y',$record['AdsGroup']['created']),array('title'=>$this->Time->format('[:F:] d, Y  h:m a',$record['AdsGroup']['created']))),
			#$this->Time->format('d/m/Y h:m a',$record['AdsGroup']['modified']),
			array($record['AdsGroup']['id'],array('class'=>'center')),
			$actions
		);
	}
	echo $this->Html->tag("table",
		$this->Html->tag("colgroup",
			$this->Html->tag("col",null,array('span'=>1,'width'=>'15px')).
			$this->Html->tag("col",null,array('span'=>1)).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'140px')).
			#$this->Html->tag("col",null,array('span'=>1,'width'=>'140px')).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'30px')).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'150px'))
		).$this->Html->tag("thead",$this->Html->tableHeaders($th),array('class'=>'floating')).$this->Html->tag("tbody",$this->Html->tableCells($tr,array('class'=>'odd'),array('class'=>'even'))).$this->Html->tag("tfoot",$this->Html->tableHeaders($th)),array('class'=>'grid','cellspacing'=>'0','border'=>0)
	);

	echo $this->element("pagination-control-bar");
	$this->I18n->end();

}else if(isset($this->data['Xpagin']['search'])){
	echo $this->Html->div("noRecords",
		$this->Html->tag("i","","icon-search icon").
		$this->Html->tag("div","[:System.ads_groups_not_found_trash:] ".$this->Html->tag("strong",h($this->data['Xpagin']['search']))).
		$this->Form->create("Xpagin",array('url'=>$this->Paginator->url(),'id'=>'XpaginForm','class'=>'clearfix')).
		$this->Html->tag("div",
			$this->Form->input("search",array('placeholder'=>'[:search:]','label'=>false)).
			$this->Form->hidden('url',array('value'=>'','id'=>'XpaginUrl')). #necesario para las ejecuciones ajax
			$this->Form->button('<i class="icon-search"></i>',array('type'=>'submit','class'=>'btn'))
			,'searchForm span4 offset4').
		$this->Form->end().
		$this->Html->link("[:back_to_list:]",array('action'=>'trash'),array('class'=>'btn btn_primary'))
	);
}else{
	echo $this->Html->div("noRecords",
		$this->Html->tag("i","","icon-trash icon").
		$this->Html->tag("div","[:System.no_ads_groups_yet_in_trash:]").
		$this->Html->link($this->Html->tag("i","","icon-plus")."[:go_ads_group_list:]",array('action'=>'index'),array('class'=>'btn btn_success','escape'=>false))
	);
}
echo $this->Ajax->divEnd("data");
?>