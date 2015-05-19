<?php /* @var $this View */
?>
<div id="UserProfile">
	<div class="top-message">
		<h1>[:my-profile:]</h1>
	</div>

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
			<div class="personal">
				<span class="title">[:personal-info:]</span>
				<div class="data">
					<span class="label">[:celphone:]</span>
					<span class="value"><?= $record['Profile']['cel']?></span>
				</div>
				<div class="data">
					<span class="label">[:telphone:]</span>
					<span class="value"><?= $record['Profile']['tel']?></span>
				</div>
				<div class="data">
					<span class="label">[:age:]</span>
					<span class="value"><?= $record['Profile']['age']?></span>
				</div>
				<div class="data">
					<span class="label">[:birthday:]</span>
					<span class="value"><?= $record['Profile']['birthday']?></span>
				</div>
				<div class="data">
					<span class="label">[:gender:]</span>
					<span class="value"><?= $record['Profile']['gender']?></span>
				</div>
			</div>
			<div class="pass">
				<span class="title">[:user-password:]</span>
				<?php
				if($record['User']['password'] != Security::hash(Configure::read("Security.salt"),"sha1",true)){
					echo $this->Html->link("[:change-password:]",array('action'=>'change_password'),array('class'=>'btn'));
					echo $this->Html->tag("span","*******",'asterisk');
				}else{
					echo $this->Html->tag("div","[:no-password-seted:]",array('class'=>'noPass'));
					echo $this->Html->link("[:set-password:]",array('action'=>'set_password'),array('class'=>'btn-success'));
				}

				?>
			</div>
		</section>
	</div>
</div>