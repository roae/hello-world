<?php /* @var $this View */
?>
<div id="UserProfile">
	<div class="top-message">
		<h1>[:my-profile:]</h1>
	</div>
	<?= $this->Form->create("User"); ?>
	<div class="col-container">
		<section class="userCard">
			<figure>
				<?= $this->Html->image($record['Profile']['photo_url']);?>
			</figure>
				<span class="name">
					<?= $record['User']['nombre']." ".$record['User']['paterno']?>
				</span>
			<span class="username"><?= $record['User']['username']?></span>
			<?= $this->Html->link("[:edit-profile:]",array('action'=>'edit_profile'),array('class'=>'btn'));?>
			<div>
				<?= $this->Html->link("[:user-logout:]",array('action'=>'logout'),array('title'=>'[:logout:]','class'=>'logout'));?>
			</div>
		</section>
		<section class="userInfo">
			<span class="title">[:change-password:]</span>
			<?php
			echo $this->Form->input("current_password",array('label'=>'[:current_password:]','type'=>'password'));
			echo $this->Form->input("password",array('label'=>'[:password:]'));
			echo $this->Form->input("password_confirm",array('label'=>'[:password_confirm:]','type'=>'password'));
			?>
			<div class="buttons">
				<button type="submit" class="btn-success">[:guardar:]</button>
				<?= $this->Html->link("[:cancel:]",array('action'=>'profile'),array('class'=>'cancel'));?>
			</div>
		</section>
	</div>
	<?= $this->Form->end() ?>
</div>