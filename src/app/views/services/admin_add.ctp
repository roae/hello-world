<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:System.admin_services_list:]',array('action' => 'index'));$this->Html->addCrumb('[:System.admin_services_add:]');
echo $this->Form->create("Service");
?>
	<div class="contentForm">
		<div class="row-fluid">
			<div class="span8 offset2">
				<div class="span6">
					<?php
					echo $this->I18n->input("name");
					echo $this->I18n->input("description");
					?>
					<div class="help">
						<i class="icon-asterisk icon-3x"></i>
						[:help_service_form:]
					</div>
				</div>
				<div class="span6">
					<?php
					echo $this->Uploader->input('Icon',array('label'=>'[:Service_Icon:]'));
					echo $this->Uploader->input('Gallery',array('label'=>'[:Service_Gallery:]'));
					?>
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
			echo $this->Html->link('<i class="icon-remove"></i>[:cancel:]',array('action'=>'index'),array('class'=>'btn','escape'=>false));
			echo $this->Form->button('<i class="icon-ok"></i>[:save:]',array('type'=>'submit','class'=>'btn_success'));
			?>
		</div>
	</div>
<?= $this->Form->end(); ?>