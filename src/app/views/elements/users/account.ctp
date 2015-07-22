<?php
if($loggedUser['User']['group_id'] == Configure::read("Group.Anonymous")){
?>
<div class="account-container">
	<ul>
		<li>
			<a class="signin" href="">[:login:]</a>
		</li>
		<li>
			<?= $this->Html->link('[:crear-cuenta:]', array('controller'=>'users','action'=>'singin'), array('class' => 'signup')); ?>
		</li>
	</ul>
</div>
<?php
}else{
?>
<div class="loggeduser-container">
	<div class="user">
		<figure>
			<?
			if(!empty($loggedProfile['photo_url'])){
				echo $this->Html->image($loggedProfile['photo_url'],array('alt'=>sprintf("%s %s",$loggedUser['User']['nombre'],$loggedUser['User']['paterno'])));
			}else{
				echo $this->Html->tag("span",substr($loggedUser['User']['nombre'],0,1).substr($loggedUser['User']['paterno'],0,1),'capitals');
			}?>
		</figure>
		<div class="username">
			<span class="name">
				<?= h(sprintf("%s %s",$loggedUser['User']['nombre'],$loggedUser['User']['paterno']))?>
			</span>
			<span class="small">
				[:mi-perfil:]
			</span>
		</div>
		<div class="userMenu">
			<ul>
				<li>
					<?= $this->Html->link("[:ir-perfil:]",array('controller'=>'users','action'=>'profile'));?>
				</li>
				<li>
					<?= $this->Html->link("[:mis-compras:]",array('controller'=>'users','action'=>'profile','#'=>'compras'));?>
				</li>
				<li>
					<?= $this->Html->link("[:tarjeta-lealtad:]",array('controller'=>'users','action'=>'profile','#'=>'tarjeta'));?>
				</li>
				<li>
					<?= $this->Html->link("[:user-logout:]",array('controller'=>'users','action'=>'logout'),array('class'=>'logout'));?>
				</li>
			</ul>
		</div>
	</div>
</div>
<?php
}
?>