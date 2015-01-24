<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_cities_list:]',array('action' => 'index'));
$this->Html->addCrumb($record['Article']['titulo']);
echo $this->Ajax->div("data",array('class'=>'row-fluid item-view'));
?>
<div class="span8">
	<h2>[:info_Article:]</h2>
	<?= $this->element("admin/view-field",array('label'=>'[:Article_titulo_input:]','data'=>$this->Html->tag("h1",$record['Article']['titulo']).$record['Article']['contenido']));?>

</div>
<div class="span4">
	<fieldset>
		<legend>[:Article_foto:]</legend>
		<?= $this->Html->image($record['Foto']['big'],array('style'=>'width:100%;display:block;'));?>
	</fieldset>
	<?
	echo $this->element("admin/view-field",array('label'=>'[:Article_autor_input:]','data'=>$record['Article']['autor']));
	echo $this->element("admin/view-field",array('label'=>'[:Article_slug_input:]','data'=>$record['Article']['slug']));
	echo $this->element("admin/view-field",array('label'=>'[:Article_keywords_input:]','data'=>$record['Article']['keywords']));
	echo $this->element("admin/view-field",array('label'=>'[:Article_description_input:]','data'=>$record['Article']['description']));
	?>
	<fieldset class="span6">
		<legend>[:Article_categorias:]</legend>
		<ul>
			<? foreach($record['Category'] as $category){ ?>
				<li><?= $category['nombre'] ?></li>
			<? } ?>
		</ul>
	</fieldset>
	<fieldset class="span6">
		<legend>[:Article_etiquetas:]</legend>
		<ul>
			<? foreach($record['Tag'] as $tag){ ?>
				<li><?= $tag['nombre'] ?></li>
			<? } ?>
		</ul>
	</fieldset>

</div>
<div class="buttonPane">
	<div class="pull-left">
		<?php
		if($record['Article']['trash']){
			echo $this->Html->link("<i class='icon-ok'></i> [:restore:]",array('action'=>'restore',$record['Article']['id']),array('class'=>'btn noHistory','escape'=>false,'rev'=>'#data'));
		}else{
			echo $this->Html->link("<i class='icon-pencil'></i> [:edit:]",array('action'=>'edit',$record['Article']['id']),array('class'=>'btn btn_success','escape'=>false));
		}

		?>
	</div>
	<div class="pull-right">
		<?php
		if($record['Article']['trash']){
			echo $this->Html->link(
				"<i class='icon-remove-sign'></i> [:delete:]",
				array('action'=>'destroy',$record['Article']['id']),
				array('class'=>'btn_danger','data-confirm'=>'[:delete_article_titulo:]: '.h($record['Article']['titulo']).'?','escape'=>false)
			);
		}else if($trashAccess){
			echo $this->Html->link(
				"<i class='icon-trash'></i> [:delete:]",
				array('action'=>'delete',$record['Article']['id']),
				array('class'=>'btn_danger action','rel'=>'[:delete_article_titulo:]: '.h($record['Article']['titulo']).'?','escape'=>false,'rev'=>'#data')
			);
		}else{
			echo $this->Html->link(
				"<i class='icon-trash'></i> [:delete:]",
				array('action'=>'delete',$record['Article']['id']),
				array('class'=>'btn_danger','data-confirm'=>'[:delete_article_titulo:]: '.h($record['Article']['titulo']).'?','escape'=>false)
			);
		}
		?>
	</div>
</div>
<?php
echo $this->Ajax->divEnd("data");
?>

