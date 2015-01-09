<?php
echo $this->Form->create("User",array('action'=>'add'));
?>
<div class="pane floating" style="width:480px;margin:0 auto;">
	<h3 class="subtitle">&raquo;&nbsp;[:add_user_form:]</h3>
	 <?= $this->Html->image("/img/icons/48/add.png",array('alt'=>'add','class'=>'iconPane'));?>
	<div class="paneContent clearfix">
		<?php
		echo $this->I18n->input('nombre',array('div'=>array('class'=>'input text','style'=>'margin:10px;')));
		echo $this->I18n->input('username',array('div'=>array('class'=>'input text min')));
		echo $this->I18n->input('group_id',array('div'=>array('class'=>'input select min')));
		echo $this->I18n->input('password',array('div'=>array('class'=>'input text min')));
		echo $this->I18n->input('password_confirm',array('type'=>'password','div'=>array('class'=>'input text min')));
		?>
	</div>
	<div class="buttonPane">
		<div class="lButtons">
			<?php echo $this->Form->input('status',array('type'=>'radio','options'=>array(1=>'[:published:]',0=>'[:unpublished:]'),'legend'=>false,'value'=>1,'div'=>array('class'=>'ui-radio'),'fieldset'=>false)); ?>
		</div>
		<div class="rButtons">
			<?php
			echo $this->Form->button("[:cancel:]",array('type'=>'reset','class'=>'button'));
			echo $this->Form->button("[:edit_user_button:]",array('type'=>'submit','class'=>'button'));
			?>
		</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>