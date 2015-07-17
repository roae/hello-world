<?php /* @var $this View */
$this->Html->script("https://www.google.com/recaptcha/api.js",array('inline'=>false));
?>
<div id="Contact-container">
	<div class="top-message">
		<h1>[:Contact_title:]</h1>
		[:contacts_text:]
	</div>
	<div class="col-container">
		<div class="ContactForm">
			<?php
			echo $this->Form->create("Contact");
				echo $this->I18n->inputs(array(
					'name'=>array('div'=>array('class'=>'input text min')),
					'email'=>array('div'=>array('class'=>'input text min')),
					'subject',
					'legend'=>false,'fieldset'=>false
				));
				echo $this->I18n->input("message");

				echo $this->Html->tag("div","",array('class'=>'g-recaptcha','data-sitekey'=>Configure::read("reCAPTCHA.site-key")));

				echo $this->Form->button("[:send-message:]",array('class'=>'btn btn_principal','type'=>'submit'));
			echo $this->Form->end();
			?>

		</diV>
		<aside>
			<?= $this->element("locations/list");?>
		</aside>
	</div>
</div>