<?php
$this->Html->addCrumb('[:admin_users:]',array('action' => 'index'));$this->Html->addCrumb('[:admin_users_edit:]');
echo $this->Form->create("User",array('url'=>$this->Html->url()));
echo $this->Form->hidden("id");
?>
<div class="contentForm">
	<div class="centered row-fluid">
		<div class="help">
			<i class="icon-asterisk icon-3x"></i>
			[:System.user_edit_help:]
		</div>
		<div class="span6">
		<?php
		echo $this->I18n->inputs(array(
				"nombre",
				"paterno",
				"materno",
				'email',
				'fieldset'=>false
			));
		?>
		</div>
		<div class="span6">
			<?php echo $this->I18n->inputs(array(
				"group_id",
				"username",
				'fieldset'=>false
			));?>
		</div>
	</div>
</div>
<div class="buttonPane">
	<div class="rButtons">
		<?php
		echo $this->Html->link("<i class='icon-remove'></i>[:System.cancel:]",array('action'=>'index'),array('class'=>'btn btn_danger','escape'=>false));
		echo $this->Form->button("<i class='icon-ok'></i>[:System.edit_user_button:]",array('type'=>'submit','class'=>'btn btn_success'));
		?>
	</div>
	<div class="lButtons">
		<?php echo $this->I18n->input('status',array('type'=>'radio','options'=>array(1=>'[:System.published:]',0=>'[:System.unpublished:]'),'legend'=>false,'value'=>1,'div'=>array('class'=>'radioButtons'),'fieldset'=>false)); ?>
	</div>
</div>
<?php echo $this->Form->end();?>