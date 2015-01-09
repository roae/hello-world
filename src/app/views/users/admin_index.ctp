<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_users_list:]');

echo $this->Ajax->div("data",array('class'=>'pagination'));
$this->Paginator->options(array('rev' => '#data','url' => am(array('controller' => 'users','action' => 'index','admin' => 1),$this->params['named'])));
echo $this->Form->create("Xpagin",array('url'=>$this->Paginator->url(),'id'=>'XpaginForm'));
echo $this->Form->hidden('url',array('value'=>'','id'=>'XpaginUrl'));#necesario para las ejecuciones ajax
$this->I18n->start();
	echo $this->Html->tag("div",
			$this->Html->tag("div",
					$this->Html->tag("label","[:acciones_por_lote:]").
					$this->Html->tag('button',$this->Html->tag("i","","icon-circle")."<span>[:active:]</span>",array('type'=>'submit','name'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'status',1)),'onclick'=>'return paginAction(this,"[:message_confirm_active_mutiple_users:]")','class'=>'btn btn_primary','title'=>'[:active_users_selected:]')).
					$this->Html->tag('button',$this->Html->tag("i","","icon-circle-blank")."<span>[:deactive:]</span>",array('type'=>'submit','name'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'status',0)),'onclick'=>'return paginAction(this,"[:message_confirm_deactive_mutiple_users:]")','class'=>'btn','title'=>'[:deactive_users_selected:]')).
					$this->Html->tag('button',$this->Html->tag("i","","icon-trash")."<span>[:delete_User:]</span>",array('type'=>'submit','name'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'delete')),'onclick'=>"return paginAction(this,'[:warning_message_delete_multiple_users:]')",'class'=>'btn btn_danger','title'=>'[:delete_users_selected:]')).
					$this->Html->link($this->Html->tag("i","","icon-plus")."<span>[:add_user:]</span>",array('action'=>'add'),array('escape'=>false,'class'=>'btn btn_success'))
				,array('class'=>'rTools'))
		,array('class'=>'tools')
	);
	$th = array(
		$this->Form->checkbox("Xpagin.all",array('class'=>'checkAll','id'=>'')),
		#$this->Paginator->sort('<span>[:nombre:]</span><span class="sortind"></span>','User.nombre',array('title'=>'[:sort_by:] [:User_nombre:]','escape'=>false)),
		$this->Paginator->sort('<span>[:User_username:]</span><span class="sortind"></span>','User.username',array('title'=>'[:sort_by:] [:User_username:]','escape'=>false)),
		$this->Paginator->sort('<span>[:User_group:]</span><span class="sortind"></span>','Group.name',array('title'=>'[:sort_by:] [:User_group:]','escape'=>false)),
		$this->Html->tag("span",'[:User_status:]'),
		$this->Paginator->sort('<span>[:User_created:]</span><span class="sortind"></span>','User.created',array('title'=>'[:sort_by:] [:User_created:]','escape'=>false)),
		$this->Paginator->sort('<span>[:User_modified:]</span><span class="sortind"></span>','User.modified',array('title'=>'[:sort_by:] [:User_modified:]','escape'=>false)),
		$this->Paginator->sort('<span>ID</span><span class="sortind"></span>','User.id',array('title'=>'[:sort_by:] [:User_id:]','escape'=>false)),
		$this->Html->tag("span","[:admin_actions:]")
	);
	$tr=array();
	foreach((array)$recordset as $count=>$record){
		/*$actions=$this->Html->div('row-actions',
			$this->Paginator->link("[:editar:]",array('action'=>'edit',$record['User']['id']),array('rev'=>'','class'=>'btn btn_primary')).
			$this->Paginator->link(($record['User']['status'])? "[:deactive:]" : "[:active:]",array('action'=>'status',($record['User']['status'])?0:1,$record['User']['id']),array('class'=>'action btn','rel'=>'[:change_status_User_username:]: <span class="itemName">'.h($record['User']['username']).'</span>?')).
			$this->Paginator->link("[:delete:]",array('action'=>'delete',$record['User']['id']),array('class'=>'action btn btn_danger','rel'=>'[:delete_User_username:]: '.h($record['User']['username']).'?'))
		);*/
		$actions= $this->Html->tag("div",
				$this->Paginator->link("<i class='icon-pencil'></i>",array('action'=>'edit',$record['User']['id']),array('rev'=>'','class'=>'btn_primary','escape'=>false)).
				$this->Paginator->link("<i class='icon-key'></i>",array('action'=>'password',$record['User']['id']),array('rev'=>'','class'=>'btn_info','escape'=>false)).
				(($record['User']['status']) ?
					$this->Paginator->link("<i class='icon-circle-blank'></i>",array('action'=>'status',0,$record['User']['id']),array('class'=>'action btn','rel'=>"[:change_status_User_username:]: <span class='itemName'>".h($record['User']['username'])."</span>?",'escape'=>false)) :
					$this->Paginator->link("<i class='icon-circle'></i>",array('action'=>'status',1,$record['User']['id']),array('class'=>'action btn_success','rel'=>"[:change_status_User_username:]: <span class='itemName'>".h($record['User']['username'])."</span>?",'escape'=>false))
				).
				$this->Paginator->link("<i class='icon-trash'></i>",array('action'=>'delete',$record['User']['id']),array('class'=>'action btn btn_danger','rel'=>'[:delete_User_username:]: '.h($record['User']['username']).'?','escape'=>false))
			,"btn-group");
		$tr[]=array(
			$this->Form->checkbox("Xpagin.record][",array('class'=>'check','id'=>'','value'=>$record['User']['id'])),
			#$this->Html->link($record['User']['nombre'],array('action' => 'edit',$record['User']['id']),array('escape'=>false,'class'=>'highlight','title'=>'[:edit:] '.$record['User']['nombre'],'rev'=>'#form')),
			$this->Html->link($record['User']['username'],array('action' => 'edit',$record['User']['id']),array('escape'=>false,'rev'=>'' ,'class'=>'highlight','title'=>'[:edit:] '.$record['User']['username'])),
			$record['Group']['name'],
			($record['User']['status'])? $this->Html->tag("span","[:active:]","label label-success"):$this->Html->tag("span","[:inactive:]","label label-warning"),
			$this->Time->format('d/m/Y h:m a',$record['User']['created']),
			$this->Time->format('d/m/Y h:m a',$record['User']['modified']),
			array($record['User']['id'],array('class'=>'center')),
			$actions
		);
	}
echo $this->Html->tag("table",
		$this->Html->tag("colgroup",
			$this->Html->tag("col",null,array('span'=>1,'width'=>'15px')).
			$this->Html->tag("col",null,array('span'=>2)).
			$this->Html->tag("col",null,array('span'=>3,'width'=>'140px')).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'50px')).
			$this->Html->tag("col",null,array('span'=>1,'width'=>'100px'))
		).$this->Html->tag("thead",$this->Html->tableHeaders($th)).$this->Html->tag("tbody",$this->Html->tableCells($tr,array('class'=>'odd'),array('class'=>'even'))).$this->Html->tag("tfoot",$this->Html->tableHeaders($th)),array('class'=>'grid','cellspacing'=>'0','border'=>0)
	);

echo $this->element('pagination-control-bar');
$this->I18n->end();
echo $this->Form->end();
echo $this->Ajax->divEnd("data");
?>
