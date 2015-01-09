<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));

echo $this->Ajax->div("ajaxForm",array('class'=>'span4'));
	echo $this->I18n->process($this->element("terms/form"));
echo $this->Ajax->divEnd("ajaxForm");

$this->Html->addCrumb("[:admin_terms_{$this->params['class']}:]");
echo $this->Ajax->div("data",array('class'=>'pagination span8'));
$url = am(array('controller' => 'terms','action' => 'index','admin' => 1,'class'=>$this->params['class']),$this->params['named']);
$this->Paginator->options(array('rev' => '#data','url' => $url));
echo $this->Form->create("Xpagin",array('url'=>$url,'id'=>'XpaginForm','class'=>'ajaxForm','data-update'=>'','data-div'=>'data'));
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
				$this->Html->tag("label","[:System.acciones_por_lote:]").
				$this->Html->tag('button',$this->Html->tag("i","","icon-trash")."<span>[:delete_terms:]</span>",array('type'=>'submit','title'=>'data[Xpagin][url]','value'=>$this->Html->url(array('action'=>'delete')),'onclick'=>"return paginAction(this,'[:warning_message_delete_multiple_terms:]')",'class'=>'btn btn_danger')).
				$this->Html->link($this->Html->tag("i","","icon-plus")."<span>[:add_{$this->params['class']}:]</span>",array('action'=>'add','class'=>$this->params['class']),array('escape'=>false,'class'=>'btn btn_success action noHistory','rev'=>"#ajaxForm"))
			,'rTools')
		,array('class'=>'tools floating')
	);
	$th = array(
		$this->Form->checkbox("Xpagin.all",array('class'=>'checkAll','id'=>'','checked'=>'')),
		$this->Paginator->sort('<span>[:Term_nombre:]</span><span class="sortind"></span>','Term.nombre',array('title'=>'[:sort_by:] [:Term_nombre:]','escape'=>false)),
		$this->Paginator->sort('<span>[:Term_descripcion:]</span><span class="sortind"></span>','Term.created',array('title'=>'[:sort_by:] [:Term_descripcion:]','escape'=>false)),
		$this->Paginator->sort('<span>[:Term_slug:]</span><span class="sortind"></span>','Term.slug',array('title'=>'[:sort_by:] [:Term_slug:]','escape'=>false)),
		$this->Paginator->sort('<span>[:Term_cantidad:]</span><span class="sortind"></span>','Term.cantidad',array('title'=>'[:sort_by:] [:Term_cantidad:]','escape'=>false)),
		'&nbsp;'
	);
echo $this->Html->tag("table",
		$this->Html->tag("colgroup",
			$this->Html->tag("col",null,array('span'=>1,'width'=>'15px')).
			$this->Html->tag("col",null,array('span'=>3)).
			#$this->Html->tag("col",null,array('span'=>2,'width'=>'140px')).
			#$this->Html->tag("col",null,array('span'=>1,'width'=>'30px')).
			$this->Html->tag("col",null,array('span'=>2,'width'=>'50px'))
		).$this->Html->tag("thead",$this->Html->tableHeaders($th),array('class'=>'floating')).$this->Html->tag("tbody",$this->element("terms/tree")).$this->Html->tag("tfoot",$this->Html->tableHeaders($th)),array('class'=>'grid','cellspacing'=>'0','border'=>0)
	);

echo $this->element('pagination-control-bar',array('class'=>'nofixed'));
$this->I18n->end();
echo $this->Form->end();
echo $this->Ajax->divEnd("data");
?>
