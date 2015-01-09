<?php
/* @var $this View */
$this->Html->addCrumb('[:admin_comments_list:]');

echo $this->Ajax->div("data",array('class'=>'pagination'));
$this->Paginator->options(array('rev' => '#data','url' => am(array('controller' => 'comments','action' => 'index','admin' => 1),$this->params['named'])));
echo $this->Form->create("Xpagin",array('url'=>$this->Paginator->url(),'id'=>'XpaginForm'));
echo $this->Form->hidden('url',array('value'=>'','id'=>'XpaginUrl'));#necesario para las ejecuciones ajax
$this->I18n->start();
	echo $this->Html->tag("div",
		$this->Html->tag("div",
			$this->Html->tag("label","[:System.acciones_por_lote:]").
			$this->Html->tag('button',"<span>[:marcar_aprobado:]</span>",array('type'=>'submit','class'=>'btn_success marcAprobar','name'=>'data[Xpagin][url]','value'=>'/admin/comments/status/1','onclick'=>'return paginAction(this,"[:message_confirm_aprobar_mutiple_comments:]")')).
			$this->Html->tag('button',"<span>[:marcar_rechazado:]</span>",array('type'=>'submit','class'=>'btn marcRechazar','name'=>'data[Xpagin][url]','value'=>'/admin/comments/status/0','onclick'=>'return paginAction(this,"[:message_confirm_rechazar_mutiple_comments:]")')).
			$this->Html->tag('button',"<span>[:marcar_spam:]</span>",array('type'=>'submit','class'=>'btn_warning marcSpam','name'=>'data[Xpagin][url]','value'=>'/admin/comments/status/2','onclick'=>'return paginAction(this,"[:message_confirm_spam_mutiple_comments:]")')).
			$this->Html->tag('button',"<span>[:delete_comment:]</span>",array('type'=>'submit','class'=>'btn_danger marcPapelera','name'=>'data[Xpagin][url]','value'=>'/admin/comments/status/3','onclick'=>'return paginAction(this,"[:warning_message_delete_multiple_comments:]")'))
		,array('class'=>'rTools')).
		$this->Html->tag("div",
			$this->I18n->input($this->params['named']['class'],array('class'=>'foreignId','empty'=>"[:choose_{$this->params['named']['class']}:]"))
		,array('class'=>'lTools'))
	,array('class'=>'tools'));
	echo $this->Html->tag("div",
		$this->Html->tag("div",
			$this->Form->checkbox("Xpagin.all",array('class'=>'checkAll','id'=>'')).
			$this->Paginator->link("<span>[:todos:]</span>",array('action'=>'index','status'=>null),array('escape'=>false,'rev'=>'#data','class'=>(!isset($this->params['named']['status']))? 'selected' : '')).
			$this->Paginator->link("<span>[:Pendientes:]</span>",array('action'=>'index','status'=>0),array('escape'=>false,'rev'=>'#data','class'=>(isset($this->params['named']['status']) && $this->params['named']['status']==="0")? 'pendientes selected' : 'pendientes')).
			$this->Paginator->link("<span>[:Aprobados:]</span>",array('action'=>'index','status'=>1),array('escape'=>false,'rev'=>'#data','class'=>(isset($this->params['named']['status']) && $this->params['named']['status']==1)? 'aprobados selected' : 'aprobados')).
			$this->Paginator->link("<span>[:spam:]</span>",array('action'=>'index','status'=>2),array('escape'=>false,'rev'=>'#data','class'=>(isset($this->params['named']['status']) && $this->params['named']['status']==2)? 'spams selected' : 'spams')).
			$this->Paginator->link("<span>[:papelera:]</span>",array('action'=>'index','status'=>3),array('escape'=>false,'rev'=>'#data','class'=>(isset($this->params['named']['status']) && $this->params['named']['status']==3)? 'papelera selected' : 'papelera'))
		,array('class'=>'lTabs'))
	,array('class'=>'tools'));


	echo $this->element("comments/admin_tree",array('recordset',$recordset));

	echo $this->element('pagination-control-bar');
$this->I18n->end();
echo $this->Ajax->divEnd("data");
?>