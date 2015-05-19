<?php /* @var $this View */
//if(!isset($this->params['pass'][0]) || empty($this->params['pass'][0])){
	$this->Html->script("https://www.google.com/recaptcha/api.js",array('inline'=>false));
?>
	<div id="register-container">
		<header class="top-message">
			<h1>[:singnin-on-citicinemas:]</h1>
			<p>[:singnin-on-citicinemas-txt:]</p>
		</header>

		<div class="col-container">

			<div class="social-connect">
				[:social-singin:]

				<ul class="social-networks">
					<li class="social-network">
						<?= $this->Html->link("Facebook",array('controller'=>'users','action'=>'singin','facebook'),array('class'=>'facebook'))?>
					</li>
					<li class="social-network">
						<?= $this->Html->link("Twitter",array('controller'=>'users','action'=>'singin','twitter'),array('class'=>'twitter'))?>
					</li>
					<li class="social-network">
						<?= $this->Html->link("Google+",array('controller'=>'users','action'=>'singin','google'),array('class'=>'google'))?>
					</li>
				</ul>
			</div>
			<?php
			echo $this->Form->create("User");
				echo $this->Html->tag("div",'[:manual-singin:]');
				echo $this->I18n->input("nombre");
				echo $this->I18n->input("paterno");
				echo $this->I18n->input("materno");
				echo $this->I18n->input("username");
				echo $this->I18n->input("password");
				echo $this->I18n->input("password_confirm",array('type'=>'password'));
				echo $this->Html->tag("div","",array('class'=>'g-recaptcha','data-sitekey'=>Configure::read("reCAPTCHA.site-key")));
				echo $this->Form->button("[:singin-today:]",array('class'=>'','type'=>'submit'));
			echo $this->Form->end();
			?>
		</div>
	</div>
<?php
#}else{
	/*App::import('Vendor','HybridAuth/Auth');
	Configure::write("HybridAuth.base_url",$this->Html->url(array('controller'=>'users','action'=>'singin',"facebook")));

	$hybridauth = new Hybrid_Auth(Configure::read("HybridAuth"));
	$adapter = $hybridauth->authenticate( "facebook" );
	$user_profile = $adapter->getUserProfile();*/
#}
?>