<div id="thanks">
	<div class="col-container">
		<div class="message">
			<div>[:message-sended:]</div>

			<?= $this->Html->link("[:back-home:]","/",array('class'=>'btn'));?>
			<?= $this->Html->link("[:go-to-blog:]",array('controller'=>'articles','action'=>'index'),array('class'=>'btn btn-primary'));?>
		</div>
	</div>
</div>