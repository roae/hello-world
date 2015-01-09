<span class="title">[:recomended_for_you:]</span>
<div class="article link">
	<?php
	echo $this->Html->image($recomended['Foto']['mini'],array('alt'=>$recomended['Article']['titulo']));
	echo $this->Html->tag("h4",$this->Html->link($recomended['Article']['titulo'],array('controller'=>'articles','action'=>'view','id'=>$recomended['Article']['id'],'slug'=>$recomended['Article']['slug']),array('title'=>$recomended['Article']['titulo'],'class'=>'fwd')),array('class'=>'articleTitle'));
	?>
</div>
<button type="button" class="close"></button>