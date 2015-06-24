<div class="col-container">
	<div class="session-expired-buy-error">
		<div class="title">
			[:session-buy-expired-title:]
		</div>
		[:session-buy-expired-error-message:]
		<?= $this->Html->link("[:back-billboard:]",array( 'controller' => 'shows','action' => 'index','slug' => Inflector::slug( low( $CitySelected['name'] ), '-' )),array('class'=>'btn btn-primary'));?>
	</div>
</div>