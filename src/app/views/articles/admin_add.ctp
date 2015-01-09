<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:System.admin_articles:]',array('action' => 'index'));$this->Html->addCrumb('[:System.admin_articles_add:]');
echo $this->Form->create("Article");
?>
<div class="contentForm">
	<div class="main">
		<div  class="tabs">
			<ul>
				<li><a href="#es">[:System.spanish:]</a></li>
				<li><a href="#en">[:System.english:]</a></li>
			</ul>
			<div id="en">
				<?php echo $this->I18n->inputs(
					array(
						"titulo"=>array('langs'=>'en_us'),
						"contenido"=>array('type'=>'textarea','class'=>'tiny','style'=>'height:1200px;','langs'=>'en_us','interpreter'=>'no'),
						'fieldset'=>false
					)
				); ?>

			</div>
			<div id="es">
				<?php echo $this->I18n->inputs(
						array(
							"titulo"=>array('langs'=>'es_mx'),
							"contenido"=>array('type'=>'textarea','class'=>'tiny','style'=>'height:1200px;','langs'=>'es_mx','interpreter'=>'no'),
							'fieldset'=>false
						)
				); ?>
			</div>
		</div>
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
				<div  class="tabs">
					<ul>
						<li><a href="#seo_es">[:System.seo_spanish:]</a></li>
						<li><a href="#seo_en">[:System.seo_english:]</a></li>
					</ul>
					<div id="seo_en">
						<?php echo $this->I18n->inputs(
							array(
								"slug"=>array('langs'=>'en_us'),
								'keywords'=>array('langs'=>'en_us'),
								'description'=>array('type'=>'textarea','langs'=>'en_us'),
								'fieldset'=>false
							)
						); ?>
					</div>
					<div id="seo_es">
						<?php echo $this->I18n->inputs(
							array(
								"slug"=>array('langs'=>'es_mx'),
								'keywords'=>array('langs'=>'es_mx'),
								'description'=>array('type'=>'textarea','langs'=>'es_mx'),
								'fieldset'=>false
							)
						); ?>
					</div>
				</div>
				<div class="help">
					<i class="icon-asterisk icon-3x"></i>
					[:System.seo_help:]
				</div>
			</div>
		</div>
	</aside>
</div>
<div class="buttonPane">
	<div class="lButtons">
		<?php echo $this->I18n->input('status',array('type'=>'radio','options'=>array(1=>'[:System.published:]',0=>'[:System.unpublished:]'),'legend'=>false,'value'=>1,'div'=>array('class'=>'radioButtons'),'fieldset'=>false)); ?>
	</div>
	<div class="rButtons">
		<?php
		echo $this->Html->link('<i class="icon-remove"></i>[:cancel:]',array('action'=>'index'),array('class'=>'btn_danger','escape'=>false));
		echo $this->Form->button('<i class="icon-ok"></i>[:save:]',array('type'=>'submit','class'=>'btn_success'));
		?>
	</div>
</div>
<?= $this->Form->end(); ?>