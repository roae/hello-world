<div class="login">
	<h1>[:Iniciar_sesion:]</h1>
	<?php
	echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' =>'login')));
		echo $this->Form->input('User.username',array('placeholder'=>'[:username:]','label'=>false,'class'=>'username'));
		echo $this->Form->input('User.password',array('placeholder'=>'[:password:]','label'=>false,'class'=>'password'));
		echo $this->Form->button('[:Login:]',array('class'=>'btn btn_primary'));
	echo $this->Form->end();
	?>
</div>