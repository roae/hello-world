<div id="LoginForm">
	<span class="tape"></span>
<?= $this->Form->create("User",array('url'=>array('controller'=>'users','action'=>'login'))); ?>
	[:titulo-panel-login:]
	<?php
		echo $this->I18n->input("username",array('after'=>'<span class="username"></span>'));
		echo $this->I18n->input("password",array('after'=>'<span class="password"></span>'));
	?>
	<div class="group">
	<?php
		echo $this->I18n->input("rememberme",array('type'=>'checkbox'));
		echo $this->Form->button("[:login:]",array('type'=>'submit','class'=>'button'));
	?>
	</div>
	[:ayuda-panel-login:]
	<?= $this->Html->link('[:registrate:]','/[:registro_url:]/',array('class'=>'signup')); ?>
	<!--<span class="shadow"></span>-->
<?= $this->Form->end(); ?>
</div>