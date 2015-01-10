<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:System.admin_cities_list:]',array('action' => 'index'));$this->Html->addCrumb('[:System.admin_cities_add:]');
echo $this->Form->create("Location");
echo $this->Form->hidden("id");
?>
<div class="contentForm">
	<div class="row-fluid">
		<div class="span9">
			<div class="span6">
				<?php
				echo $this->I18n->input("name");
				echo $this->I18n->input("phone_numbers");
				?>

			</div>
			<div class="span6">
				<?php
				echo $this->I18n->inputs(array(
					'vista_code',
					'vista_service_url',
					'legend'=>'[:vista_System:]',
				));
				?>
			</div>
			<fieldset class="span12">
				<legend>[:location_address:]</legend>
				<?php
				echo $this->I18n->inputs(array(
					'street'=>array('div'=>array('class'=>'input text required span6')),
					'interior'=>array('div'=>array('class'=>'input text required span3')),
					'outside'=>array('div'=>array('class'=>'input text span3')),
					'neighborhood'=>array('div'=>array('class'=>'input text required span6')),
					'zip'=>array('div'=>array('class'=>'input text required span3')),
					"city_id"=>array('div'=>array('class'=>'input select required span6')),
					'state'=>array('div'=>array('class'=>'input text required span6')),
					'fieldset'=>false,

				));
				echo $this->Html->div("span12",$this->element("locations/map"));
				?>
			</fieldset>
		</div>
		<div class="span3">
			<?= $this->Uploader->input('Gallery',array('label'=>'[:location_gallery:]')) ?>
			<div class="help">
				<i class="icon-asterisk icon-3x"></i>
				[:help_city_form:]
			</div>
		</div>
	</div>
</div>
<div class="buttonPane">
	<div class="lButtons">
		<?php #echo $this->I18n->input('status',array('type'=>'radio','options'=>array(1=>'[:System.published:]',0=>'[:System.unpublished:]'),'legend'=>false,'value'=>1,'div'=>array('class'=>'radioButtons'),'fieldset'=>false)); ?>
	</div>
	<div class="rButtons">
		<?php
		echo $this->Html->link('<i class="icon-remove"></i>[:cancel:]',array('action'=>'index'),array('class'=>'btn_danger','escape'=>false));
		echo $this->Form->button('<i class="icon-ok"></i>[:save:]',array('type'=>'submit','class'=>'btn_success'));
		?>
	</div>
</div>
<?= $this->Form->end(); ?>

<script type="text/javascript">
	var Cities = <?= $this->Javascript->object($cities); ?> ;
</script>