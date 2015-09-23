<div class="col-container">
	<div class="session-buy-error">
			[:session-buy-error-message:]

			<?php
			if(!isset($this->params['url']['mobile'])){
				echo $this->Html->link("[:back-billboard:]",array( 'controller' => 'shows','action' => 'index','slug' => Inflector::slug( low( $CitySelected['name'] ), '-' )),array('class'=>'btn'));
			}
			?>
	</div>
</div>