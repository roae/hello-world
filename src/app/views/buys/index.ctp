<div id="MobileBuys">
	<?php
	if(!empty($recordset)){
		?>
		<span class="title">[:device-buys:]</span>
		<ul class="moviesList">
			<?php foreach($recordset as $record): ?>
				<li class="movie">
					<figure>
						<?= $this->Html->image($this->Uploader->generatePath($record['Movie']['Poster'],'mini'));?>
					</figure>
					<span class="movie-title"><?= h($record['Movie']['title'])?></span>
					<span class="data">[:buy-no-confirmation:]: <span class="value"><?= $record['Buy']['confirmation_number']?></span></span>
					<span class="data">[:buy-date:]: <span class="value"><?= $this->Time->format("[:l:] d [:F:], Y",$record['Buy']['created']);?></span></span>
					<?php
						$url = $this->Html->url(array('controller'=>'buys','action'=>'view',$record['Buy']['confirmation_number']))."?mobile=".$record['Buy']['device'];
						echo $this->Html->link("[:more-details:]",$url,array('class'=>'btn'));
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php
	}else{
		?>
		<div class="noBuys">
			[:no-buys-yet:]
		</div>
	<?php
	}
	?>
</div>