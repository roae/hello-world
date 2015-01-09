<?php
$this->set("sectionTitle","[:contact-title:]");
$this->Html->addCrumb("[:m_contacts:]");
?>
<div id="Contact">

	<div class="Wrapper">
		<div class="ContactForm mainContent">
			<div class="h1">[:Contactanos:]</div>
			[:contact_text_title:]
			<?php
			echo $this->Form->create("Contact");
				echo $this->I18n->inputs(array(
					'name',
					'email',
					'subject',
					'legend'=>false,'fieldset'=>false
				));
				echo $this->I18n->input("message");

				echo $this->I18n->input("captcha",array(
					'before'=>$this->Html->image("/contacts/captcha/contact/".rand(0,  getrandmax()),array('alt'=>'captcha','id'=>'captchaComment')).$this->Html->tag("i","",'icon block'),
					'div'=>array('class'=>'input text captcha'),
					'after'=>$this->Html->link('[:change_code:]','#',array('escape'=>false,'onclick'=>"$('#captchaComment').attr('src','/contacts/captcha/comment/'+Math.random());return false;"))
				));

				echo $this->Form->button("[:send-message:]",array('class'=>'btn btn_principal','type'=>'submit'));
			echo $this->Form->end();
			?>

		</diV>
		<aside>
			<?= $this->element("searchbox");?>
			<div class="panel">
				<div class="h3">[:contact_info:]</div>
				<div class="contactInfo">
					<span class="data address">[:address:]</span>
					<span class="data phone">[:phone:]</span>
					<span class="data email">[:email:]</span>
					<span class="data emailsupport">[:emailsupport:]</span>
				</div>
			</div>
			<?= $this->element("social"); ?>
		</aside>
	</div>
</div>