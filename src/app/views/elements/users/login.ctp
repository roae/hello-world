<div id="login-container">

	<a id="login-close" href="">Close</a>

	<div class="login">

		<strong class="title">Inicia sesión en Citicinemas usando tus redes sociales</strong>

		<?= $this->Form->create("User",array('url'=>array('controller'=>'users','action'=>'login'),'id'=>'UserLoginForm')); ?>
		<ul class="social-connect">
			<li>
				<?= $this->Html->link("Facebook",array('controller'=>'users','action'=>'singin','facebook'),array('class'=>'fb'))?>
			</li>
			<li>
				<?= $this->Html->link("Twitter",array('controller'=>'users','action'=>'singin','twitter'),array('class'=>'tw'))?>
			</li>
			<li>
				<?= $this->Html->link("Google+",array('controller'=>'users','action'=>'singin','google'),array('class'=>'gp'))?>
			</li>
		</ul>

			<strong class="title">O ingresa tus datos</strong>
			<?php
			echo $this->Form->input("username",array('label'=>'[:username-or-email:]'));
			echo $this->Form->input("password",array('label'=>'[:username-password:]'));
			?>

			<button type="submit">[:user-login:]</button>

		<?= $this->Form->end();?>
	</div>
</div>