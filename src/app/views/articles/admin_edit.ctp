<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:admin_articles:]',array('action' => 'index'));$this->Html->addCrumb('[:admin_articles_edit:]');
echo $this->Form->create("Article",array('url'=>$this->Html->url()));
echo $this->Form->hidden("id");
?>
<div class="contentForm">
	<div class="main">
		<?php echo $this->I18n->inputs(
			array(
				"titulo"=>array('langs'=>'es_mx'),
				"contenido"=>array('type'=>'textarea','class'=>'tiny','style'=>'height:1200px;','langs'=>'es_mx','interpreter'=>'no'),
				'fieldset'=>false
			)
		); ?>
	</div>
	<aside>
		<?= $this->Uploader->input('Foto',array('label'=>'Imagen del articulo','div'=>array('style'=>'width:auto;'))); ?>
		<div class="panel">
			<div class="panel-title">[:autor-article:]</div>
			<div class="panel-content">
				<?= $this->I18n->input("autor",array('label'=>false)); ?>
			</div>
		</div>
		<?php
		echo $this->element("terms/categories");
		echo $this->element("terms/tags");
		?>
		<div class="panel">
			<div class="panel-title">[:seo-fields:]</div>
			<div class="panel-content">

				<?php echo $this->I18n->inputs(
					array(
						"slug"=>array('langs'=>'es_mx'),
						'keywords'=>array('langs'=>'es_mx'),
						'description'=>array('type'=>'textarea','langs'=>'es_mx'),
						'fieldset'=>false
					)
				); ?>

				<div class="help">
					<i class="icon-asterisk icon-3x"></i>
					[:System.seo_help:]
				</div>
			</div>
		</div>
	</aside>
</div>
<div class="buttonPane">
	<div class="rButtons">
		<?php
		echo $this->Html->link("<i class='icon-remove'></i>[:cancel:]",'/admin/articles',array('class'=>'btn btn_danger','escape'=>false));
		echo $this->Form->button("<i class='icon-ok'></i>[:edit_article_button:]",array('type'=>'submit','class'=>'btn btn_success'));
		?>
	</div>
	<div class="lButtons">
		<?php echo $this->I18n->input('status',array('type'=>'radio','options'=>array(1=>'[:published:]',0=>'[:unpublished:]'),'legend'=>false,'value'=>1,'div'=>array('class'=>'radioButtons'),'fieldset'=>false)); ?>
	</div>
</div>
<?php echo $this->Form->end();?>