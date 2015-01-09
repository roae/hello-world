<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_contacts_list:]');
echo $this->Ajax->div("data",array('class'=>'pagination'));
if(!empty($recordset)){
	$this->Paginator->options(array('rev' => '#data','url' => am(array('controller' => 'contacts','action' => 'index','admin' => 1),$this->params['named'])));
	echo $this->Form->create("Xpagin",array('url'=>$this->Paginator->url(),'id'=>'XpaginForm'));
	echo $this->Form->hidden('url',array('value'=>'','id'=>'XpaginUrl'));#necesario para las ejecuciones ajax
	$this->I18n->start();
		echo $this->Html->tag("div",
			$this->Html->tag("div",
					$this->Html->tag('button',$this->Html->tag("i","","icon-trash")."<span>[:delete_contact:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'delete')),'onclick'=>"return paginAction(this,'[:warning_message_delete_multiple_contacts:]')",'class'=>'btn btn_danger'))
				,array('class'=>'rTools'))
			,array('class'=>'tools')
		);
		$th = array(
			$this->Form->checkbox("Xpagin.all",array('class'=>'checkAll','id'=>'')),
			$this->Paginator->sort('<span>[:Contact_name:]</span><span class="sortind"></span>','Contact.name',array('title'=>'[:sort_by:] [:Contact_name:]','escape'=>false)),
			$this->Paginator->sort('<span>[:Contact_email:]</span><span class="sortind"></span>','Contact.email',array('title'=>'[:sort_by:] [:Contact_email:]','escape'=>false)),
			$this->Paginator->sort('<span>[:Contact_message:]</span><span class="sortind"></span>','Contact.messgae',array('title'=>'[:sort_by:] [:Contact_nmessage:]','escape'=>false)),
			$this->Paginator->sort('<span>[:Contact_ip:]</span><span class="sortind"></span>','Contact.ip',array('title'=>'[:sort_by:] [:Contact_ip:]','escape'=>false)),
			$this->Paginator->sort('<span>[:Contact_created:]</span><span class="sortind"></span>','Contact.created',array('title'=>'[:sort_by:] [:Contact_created:]','escape'=>false)),
			$this->Paginator->sort('<span>ID</span><span class="sortind"></span>','Contact.id',array('title'=>'[:sort_by:] [:Contact_id:]','escape'=>false)),
		);
		$tr=array();
		foreach((array)$recordset as $count=>$record){
			$actions=$this->Html->div('row-actions',
						$this->Paginator->link("[:delete:]",array('action'=>'delete',$record['Contact']['id']),array('class'=>'action danger','rel'=>'[:delete_contact_question:]: '.h($record['Contact']['name']).'?'))
					);
			$tr[]=array(
				$this->Form->checkbox("Xpagin.record][",array('class'=>'check','id'=>'','value'=>$record['Contact']['id'])),
				$record['Contact']['name'],
				$record['Contact']['email'],
				$record['Contact']['message'],
				$record['Contact']['ip'],
				$this->Time->format('d/m/Y h:m a',$record['Contact']['created']),
				array($record['Contact']['id'],array('class'=>'center'))
			);
		}
	echo $this->Html->tag("table",
			$this->Html->tag("colgroup",
				$this->Html->tag("col",null,array('span'=>1,'width'=>'15px')).
				$this->Html->tag("col",null,array('span'=>4)).
				$this->Html->tag("col",null,array('span'=>1,'width'=>'140px')).
				$this->Html->tag("col",null,array('span'=>1,'width'=>'30px'))
			).$this->Html->tag("thead",$this->Html->tableHeaders($th)).$this->Html->tag("tbody",$this->Html->tableCells($tr,array('class'=>'odd'),array('class'=>'even'))).$this->Html->tag("tfoot",$this->Html->tableHeaders($th)),array('class'=>'grid','cellspacing'=>'0','border'=>0)
		);

	echo $this->element("pagination-control-bar");
	$this->I18n->end();
}else{
	echo $this->Html->div("noRecords",
		$this->Html->tag("i","","icon-list-ul icon").
		$this->Html->tag("div","[:System.no_contacts_yet:]")
		
	);
}
echo $this->Ajax->divEnd("data");
?>