<?php /* @var $this View */ ?>
<div id="featured">
	<div class="wrapper group">
		<?= $this->Html->image('signup.png',array('alt'=>'[:alt_img_signup:]','class'=>'representacion')); ?>
		<h1 class="instruccion">[:registro-usuarios:]</h1>
	</div>
	<div class="wave"></div>
</div>
<div class="wrapper group signup">
	<div id="main">
		<?= $this->Form->create('User',array('class'=>'signupform')); ?>
			<span class="title">[:ingresa-datos-registro:]</span>
			<div>[:text-registro:]</div>
			<div class="hightlight">
				<fieldset>
					<legend>[:datos-del-usuario:]</legend>
					<?= $this->I18n->input('username'); ?>
					<div class="facebook">
						<a href="#" desc="botono de registro de facebook" title="[:registro_facebook_title:]">[:registro_facebook:]</a>
					</div>
					<?php	
						echo $this->I18n->input('password');
						echo $this->I18n->input('password_confirm',array('type'=>'password'));
					?>
				</fieldset>
			</div>
			<?php 
			echo $this->I18n->inputs(array(
				'legend'=>'[:datos-personales:]',
				'nombre','apellidos','tel','cel'
			));
			echo $this->I18n->inputs(array(
				'legend'=>'[:direccion-entrega:]',
				'calle','num'=>array('class'=>'num'),'num_int'=>array('class'=>'num_int'),'colonia','cp'
			));
			echo $this->I18n->input('newsletter',array('type'=>'checkbox','checked'=>'checked'));
			echo $this->I18n->input('terminos',array('type'=>'checkbox'));
			echo $this->I18n->input("captcha",array('div'=>array('class'=>'input text captcha'),'before'=>$this->Html->link($this->Html->image("/contacts/captcha/",array('alt'=>'','id'=>'captcha')),'#',array('onclick'=>"$('#captcha').attr('src','/contacts/captcha/'+Math.random());return false;",'escape'=>false))));
			?>
			
			<div class="buttons">
				<?= $this->Form->button('[:registrarme:]',array('type'=>'summit','class'=>'button button_h')); ?>
			</div>
		<?= $this->Form->end(); ?>
	</div>
	<div id="sideBar">
		<div class="panel">
			[:porque-usar-quieromicomida:]
		</div>
		<div class="panel motivacion">
			[:empieza-a-ordenar-gratis:]
		</div>
		<div class="panel">
			<div class="note">
				<span class="tape"></span>
				
				[:registro-con-facebook-esplicacion:]
				<div class="facebook">
					<a href="#" desc="botono de registro de facebook" title="[:registro_facebook_title:]">[:registro_facebook:]</a>
				</div>
			</div>
		</div>
	</div>
</div>