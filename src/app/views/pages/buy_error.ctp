<div class="col-container">
	<div class="session-buy-error">
			[:session-buy-error-message:]
			<?= $this->Html->link("[:back-billboard:]",array( 'controller' => 'shows','action' => 'index','slug' => Inflector::slug( low( $CitySelected['name'] ), '-' )),array('class'=>'btn'));?>
	</div>
</div>