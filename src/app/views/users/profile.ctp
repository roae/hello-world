<?php /* @var $this View */
?>
<div id="UserProfile">
	<div class="top-message">
		<h1>[:my-profile:]</h1>
	</div>

	<div class="col-container">
		<section class="userCard">
			<figure>
				<?
				if(!empty($loggedProfile['photo_url'])){
					echo $this->Html->image($record['Profile']['photo_url'],array('alt'=>sprintf("%s %s",$record['User']['nombre'],$record['User']['paterno'])));
				}else{
					echo $this->Html->tag("span",substr($record['User']['nombre'],0,1).substr($record['User']['paterno'],0,1),'capitals');
				}?>
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
			<a name="compras"></a>
			<div class="Buys">

				<span class="title">[:user-buys:]</span>
				<?php
				if(!empty($record['Buy'])){
				?>
					<ul class="moviesList">
						<?php foreach($record['Buy'] as $item): ?>
							<li class="movie">
								<figure>
									<?= $this->Html->link($this->Html->image($this->Uploader->generatePath($item['Movie']['Poster'],'mini')), array('controller' => 'movies', 'action' => 'view', 'slug' => $item['Movie']['slug']), array('escape' => false));?>
								</figure>
								<span class="movie-title"><?= h($item['Movie']['title'])?></span>
								<span class="data">[:buy-no-confirmation:]: <?= $item['confirmation_number']?></span>
								<span class="data">[:buy-date:]: <?= $this->Time->format("[:l:] d [:F:], Y",$item['created']);?></span>
								<?= $this->Html->link("[:more-details:]",array('controller'=>'buys','action'=>'view',$item['id']),array('class'=>'btn'));?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php
				}else{
				?>
					<div class="noBuys">
						[:no-buys-yet:]
					</div>
				<?php
				}
				?>
			</div>
		</section>
	</div>
</div>