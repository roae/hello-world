<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_ads_groups_list:]');
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
			#$this->Html->tag('button',$this->Html->tag("i","","icon-circle")."<span>[:publish:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'status',1)),'onclick'=>'return paginAction(this,"[:message_confirm_publish_mutiple_ads_groups:]")','class'=>'btn btn_primary')).
			#$this->Html->tag('button',$this->Html->tag("i","","icon-circle-blank")."<span>[:unpublish:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'status',0)),'onclick'=>'return paginAction(this,"[:message_confirm_unpublish_mutiple_ads_groups:]")','class'=>'btn')).
			$this->Html->tag('button',$this->Html->tag("i","","icon-remove-sign")."<span>[:delete_ads_group:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'delete')),'onclick'=>"return paginAction(this,'[:warning_message_delete_multiple_ads_groups:]')",'class'=>'btn btn_danger')).
			$this->Html->link($this->Html->tag("i","","icon-plus")."<span>[:add_ads_group:]</span>",array('action'=>'add'),array('escape'=>false,'class'=>'btn btn_success'))
			,array('class'=>'rTools'))
		,array('class'=>'tools floating')
	);
	$th = array(
		$this->Form->checkbox("Xpagin.all",array('class'=>'checkAll','id'=>'')),
		$this->Paginator->sort('<span>[:AdsGroup_title:]</span><span class="sortind"></span>','AdsGroup.title',array('title'=>'[:sort_by:] [:AdsGroup_title:]','escape'=>false)),
		$this->Paginator->sort('<span>[:AdsGroup_created:]</span><span class="sortind"></span>','AdsGroup.created',array('title'=>'[:sort_by:] [:AdsGroup_created:]','escape'=>false)),
		$this->Paginator->sort('<span>ID</span><span class="sortind"></span>','AdsGroup.id',array('title'=>'[:sort_by:] [:AdsGroup_id:]','escape'=>false)),
		'-'
	);
	$tr=array();
	foreach((array)$recordset as $count=>$record){
		$actions=$this->Html->div('btn-group',
			$this->Paginator->link($this->Html->tag("i","","icon-pencil")."[:editar:]",array('action'=>'edit',$record['AdsGroup']['id']),array('rev'=>'','class'=>'btn btn-info','escape'=>false)).
			$this->Form->button("<span class='caret'></span>",array('type'=>'button','class'=>'btn btn-info  dropdown-toggle','data-toggle'=>'dropdown')).
			$this->Html->tag("ul",
				#$this->Html->tag("li",$this->Paginator->link("[:list_locations:]",array('controller'=>'locations','action'=>'index','ads_group'=>$record['AdsGroup']['id']))).
				#$this->Html->tag("li",$this->Paginator->link("[:add_location:]",array('controller'=>'locations','action'=>'add','ads_group'=>$record['AdsGroup']['id']))).
				$this->Html->tag("li","","divider").
				$this->Html->tag("li",$this->Paginator->link("<span class='icon-trash'></span> [:delete:]",array('action'=>'delete',$record['AdsGroup']['id']),array('class'=>'action danger','rel'=>'[:System.delete_ads_group_title:]: '.h($record['AdsGroup']['name']).'?','escape'=>false)))
			,array('class'=>"dropdown-menu",'role'=>'menu'))
		);
		$tr[]=array(
			$this->Form->checkbox("Xpagin.record][",array('class'=>'check','id'=>'','value'=>$record['AdsGroup']['id'])),
			$this->Paginator->link($record['AdsGroup']['name'],array('action' => 'view',$record['AdsGroup']['id']),array('rev'=>'','escape'=>false,'class'=>'highlight','title'=>'[:edit:] '.$record['AdsGroup']['name'])),
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

	echo $this->Html->tag("div",
		$this->Html->tag("div",
			$this->Html->tag("div",
				$this->Html->link($this->Html->tag("i","","icon-trash")."<span>[:trash_ads_groups:]</span>",array('action'=>'trash'),array('class'=>'btn','escape'=>false))
			,array('class'=>'rTools'))
		,array('class'=>'tools'))
	);
	echo $this->element("pagination-control-bar");
	$this->I18n->end();

}else if(isset($this->data['Xpagin']['search'])){
	echo $this->Html->div("noRecords",
		$this->Html->tag("i","","icon-search icon").
		$this->Html->tag("div","[:System.ads_groups_not_found:] ".$this->Html->tag("strong",h($this->data['Xpagin']['search']))).
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
		$this->Html->tag("div","[:System.no_ads_groups_yet:]").
		$this->Html->link($this->Html->tag("i","","icon-plus")."[:add_ads_group:]",array('action'=>'add'),array('class'=>'btn btn_success','escape'=>false))
	);
}
echo $this->Ajax->divEnd("data");
?>