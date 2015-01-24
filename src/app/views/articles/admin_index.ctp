<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_articles_list:]');
echo $this->Ajax->div("data",array('class'=>'pagination'));
if(!empty($recordset)){
	$this->Paginator->options(array('rev' => '#data','url' => am(array('controller' => 'articles','action' => 'index','admin' => 1),$this->params['named'])));
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
						$this->Html->tag('button',$this->Html->tag("i","","icon-circle")."<span>[:publish:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'status',1)),'onclick'=>'return paginAction(this,"[:message_confirm_publish_mutiple_articles:]")','class'=>'btn btn_primary')).
						$this->Html->tag('button',$this->Html->tag("i","","icon-circle-blank")."<span>[:unpublish:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'status',0)),'onclick'=>'return paginAction(this,"[:message_confirm_unpublish_mutiple_articles:]")','class'=>'btn')).
						$this->Html->tag('button',$this->Html->tag("i","","icon-trash")."<span>[:delete_article:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'delete')),'onclick'=>"return paginAction(this,'[:warning_message_delete_multiple_articles:]')",'class'=>'btn btn_danger')).
						$this->Html->link($this->Html->tag("i","","icon-plus")."<span>[:add_article:]</span>",array('action'=>'add'),array('escape'=>false,'class'=>'btn btn_success'))
					,array('class'=>'rTools'))
			,array('class'=>'tools floating')
		);
		$th = array(
			$this->Form->checkbox("Xpagin.all",array('class'=>'checkAll','id'=>'')),
			$this->Paginator->sort('<span>[:Article_titulo:]</span><span class="sortind"></span>','Article.titulo',array('title'=>'[:sort_by:] [:Article_titulo:]','escape'=>false)),
			$this->Html->tag("span",'[:Article_published:]'),
			$this->Paginator->sort('<span>[:Article_created:]</span><span class="sortind"></span>','Article.created',array('title'=>'[:sort_by:] [:Article_created:]','escape'=>false)),
			$this->Paginator->sort('<span>[:Article_modified:]</span><span class="sortind"></span>','Article.modified',array('title'=>'[:sort_by:] [:Article_modified:]','escape'=>false)),
			$this->Paginator->sort('<span>ID</span><span class="sortind"></span>','Article.id',array('title'=>'[:sort_by:] [:Article_id:]','escape'=>false)),
			'-',
		);
		$tr=array();
		foreach((array)$recordset as $count=>$record){
			$actions=$this->Html->div('btn-group',
				$this->Paginator->link($this->Html->tag("i","","icon-pencil")."[:editar:]",array('action'=>'edit',$record['Article']['id']),array('rev'=>'','class'=>'btn btn-info','escape'=>false)).
				$this->Form->button("<span class='caret'></span>",array('type'=>'button','class'=>'btn btn-info  dropdown-toggle','data-toggle'=>'dropdown')).
				$this->Html->tag("ul",
					$this->Html->tag("li",
						$this->Paginator->link( $record['Article']['status']? $this->Html->tag("i","","icon-circle-blank")."[:unpublish:]" : $this->Html->tag("i","","icon-circle")."[:publish:]",array('action'=>'status',($record['Article']['status'])?0:1,$record['Article']['id']),array('class'=>'action '.($record['Article']['status'] ? "warning" : "success"),'rel'=>"[:change_status_article_titulo:]: <span class='itemName'>".h($record['Article']['titulo']).'</span>?','escape'=>false))
					).
					#$this->Html->tag("li",$this->Paginator->link("[:add_location:]",array('controller'=>'locations','action'=>'add','article'=>$record['Article']['id']))).
					$this->Html->tag("li","","divider").
					$this->Html->tag("li",$this->Paginator->link("<span class='icon-trash'></span> [:delete:]",array('action'=>'delete',$record['Article']['id']),array('class'=>'action danger','rel'=>'[:System.delete_article_name:]: '.h($record['Article']['titulo']).'?','escape'=>false)))
					,array('class'=>"dropdown-menu",'role'=>'menu'))
			);
			$img=isset($record['Foto']['thumb']) ? $this->Html->image($record['Foto']['thumb'],array('class'=>'pagin-img')): '';
			$tr[]=array(
				$this->Form->checkbox("Xpagin.record][",array('class'=>'check','id'=>'','value'=>$record['Article']['id'])),
				$this->Paginator->link($img.$record['Article']['titulo'],array('action' => 'view',$record['Article']['id']),array('rev'=>'','escape'=>false,'class'=>'highlight')).$this->Html->div('details',$this->Text->truncate($record['Article']['contenido'], 250, array('html'=>true,'ending'=>'[...]','exact'=>false))),
				($record['Article']['status'])? $this->Html->tag("span","[:yes:]","label label-success"):$this->Html->tag("span","[:no:]","label label-warning"),
				$this->Time->format('d/m/Y h:m a',$record['Article']['created']),
				$this->Time->format('d/m/Y h:m a',$record['Article']['modified']),
				array($record['Article']['id'],array('class'=>'center')),
				$actions
			);
		}
	echo $this->Html->tag("table",
			$this->Html->tag("colgroup",
				$this->Html->tag("col",null,array('span'=>1,'width'=>'15px')).
				$this->Html->tag("col",null,array('span'=>2)).
				$this->Html->tag("col",null,array('span'=>1,'width'=>'140px')).
				$this->Html->tag("col",null,array('span'=>1,'width'=>'140px')).
				$this->Html->tag("col",null,array('span'=>1,'width'=>'30px')).
				$this->Html->tag("col",null,array('span'=>1,'width'=>'150px'))
			).$this->Html->tag("thead",$this->Html->tableHeaders($th),array('class'=>'floating')).$this->Html->tag("tbody",$this->Html->tableCells($tr,array('class'=>'odd'),array('class'=>'even'))).$this->Html->tag("tfoot",$this->Html->tableHeaders($th)),array('class'=>'grid','cellspacing'=>'0','border'=>0)
		);

	echo $this->Html->tag("div",
		$this->Html->tag("div",
			$this->Html->tag("div",
				$this->Html->link($this->Html->tag("i","","icon-trash")."<span>[:trash_cities:]</span>",array('action'=>'trash'),array('class'=>'btn','escape'=>false))
				,array('class'=>'rTools'))
			,array('class'=>'tools'))
	);

	echo $this->element("pagination-control-bar");
	$this->I18n->end();

}else{
	echo $this->Html->div("noRecords",
		$this->Html->tag("i","","icon-list-ul icon").
		$this->Html->tag("div","[:System.no_articles_yet:]").
		$this->Html->link($this->Html->tag("i","","icon-plus")."[:add_article:]",array('action'=>'add'),array('class'=>'btn btn_success','escape'=>false))
	);
}
echo $this->Ajax->divEnd("data");
?>