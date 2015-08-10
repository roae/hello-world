<div id="Login">

	<div class="top-message">
		<h1>[:login-title:]</h1>
	</div>
	<div class="col-container">
		<div class="login">
			<div class="social-connect">
				<strong class="subtitle">[:social-login-subtitle:]</strong>
				<ul>
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
			</div>
			<?= $this->Form->create("User",array('url'=>array('controller'=>'users','action'=>'login'),'id'=>'UserLoginForm')); ?>
			<strong class="subtitle">[:data-login-subtitle:]</strong>
			<?php
			echo $this->Form->input("username",array('label'=>'[:username-or-email:]'));
			echo $this->Form->input("password",array('label'=>'[:username-password:]'));
			?>

			<button type="submit">[:user-login:]</button>

			<?= $this->Form->end();?>
		</div>
	</div>

</div>

