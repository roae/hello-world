<?php /* @var $this View */
?>
<div id="UserProfile">
	<div class="top-message">
		<h1>[:my-profile:]</h1>
	</div>

	<div class="col-container">
		<?= $this->Form->create("User"); ?>
		<section class="userCard">
			<figure>
				<?
				if(!empty($loggedProfile['photo_url'])){
					echo $this->Html->image($this->data['Profile']['photo_url'],array('alt'=>sprintf("%s %s",$this->data['User']['nombre'],$this->data['User']['paterno'])));
				}else{
					echo $this->Html->tag("span",substr($this->data['User']['nombre'],0,1).substr($this->data['User']['paterno'],0,1),'capitals');
				}?>
			</figure>
			<span class="name">
				<?= $this->data['User']['nombre']." ".$this->data['User']['paterno']?>
			</span>
			<span class="username"><?= $this->data['User']['username']?></span>
			<div>
				<?= $this->Html->link("[:user-logout:]",array('action'=>'logout'),array('title'=>'[:logout:]','class'=>'logout'));?>
			</div>
		</section>
		<section class="userInfo">
			<div class="personal">
				<span class="title">[:personal-info:]</span>
				<?php
				echo $this->Form->input("User.nombre",array('label'=>'[:nombre:]'));
				echo $this->Form->input("User.paterno",array('label'=>'[:apellido-paterno:]'));
				echo $this->Form->input("User.materno",array('label'=>'[:apellido-materno:]'));
				echo $this->Form->input("User.email",array('label'=>'[:email:]'));
				echo $this->Form->input("Profile.cel",array('label'=>'[:celphone:]'));
				echo $this->Form->input("Profile.tel",array('label'=>'[:telphone:]'));
				echo $this->Form->input("Profile.age",array('label'=>'[:age:]'));
				echo $this->Form->input("Profile.birthday",array('label'=>'[:birthday:]','type'=>'date','minYear'=>'1940','maxYear'=>date("Y")-10));
				?>
				<div class="buttons">
					<button type="submit" class="btn-success">[:guardar:]</button>
					<?= $this->Html->link("[:cancel:]",array('action'=>'profile'),array('class'=>'cancel'));?>
				</div>
			</div>
		</section>
		<?= $this->Form->end();?>
	</div>
</div>