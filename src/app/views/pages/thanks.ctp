<div id="thanks">
	<div class="col-container">
		<div class="message">
			<div>[:message-sended:]</div>

			<?php
			if(!isset($this->params['url']['mobile'])){
				echo $this->Html->link("[:back-home:]","/",array('class'=>'btn'));
				echo $this->Html->link("[:go-to-blog:]",array('controller'=>'articles','action'=>'index'),array('class'=>'btn btn-primary'));
			}
			?>
		</div>
	</div>
</div>