<?php /* @var $this View */ ?>
<div class="wrapper group">
	<div id="SuggestRestaurant" class="thanks">
		<div class="title">[:user_confirm_<?= $done ?>:]</div>
		[:user_<?= $done ?>:]
		<?php
		echo $this->Html->link("[:ir_pagina_inicio:]","/",array('class'=>'button'));
		$route=Router::parse($this->Session->read("SigninReferer"));
		if(isset($route['orden']) && $done=="successfully"){
			echo $this->Html->link("[:continuar_con_pedido:]",$this->Session->read("SigninReferer"),array('class'=>'button button_h'));
		}
		?>
	</div>
</div>
