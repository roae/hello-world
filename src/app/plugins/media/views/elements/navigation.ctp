<div class="nav">
	<?php
	echo $this->Form->button('<span class="icon"></span>',array('type'=>'button','class'=>'back','onclick'=>'history.go(-1);'));
	echo $this->Form->button('<span class="icon"></span>',array('type'=>'button','class'=>'next','onclick'=>'history.go(1);'));
	?>
</div>
<div class="viewMode">
	<a href="/admin/media<?= (!empty($path))? '/'.$path : '';?>/view:thumbnails" rev="#Navigation|Folders|Files" rel="" class="thumbnails action"><span class="icon"></span></a>
	<a href="/admin/media<?= (!empty($path))? '/'.$path : '';?>/view:list" rev="#Navigation|Folders|Files" rel="" class="list action"><span class="icon"></span></a>
	<a href="/admin/media<?= (!empty($path))? '/'.$path : '';?>/view:icons" rev="#Navigation|Folders|Files" rel="" class="icons action"><span class="icon"></span></a>
</div>
<div class="MediaUrl">
	<?php
	echo $this->Form->create("Folder",array('id'=>'MediaPathForm'));
	echo $this->Form->input("path",array('label'=>false,'value'=>"/".$path));
	echo $this->Form->button('',array('type'=>'submit'));
	echo $this->Form->end();
	?>
</div>
<div id="MediaPath">
	<?php
	if($detailPath){
		foreach($detailPath as $i=>$folder){
			if(!$i && count($detailPath)){
				echo $this->Html->link('<span class="icon"></span><span class="arrow_right"></span>','/admin/media',array('class'=>'home','escape'=>false,'style'=>'z-index:'.(count($detailPath)-$i+6).';','rev'=>'#Navigation|Folders|Files'));
			}
			if(count($detailPath)-1 > $i){
				echo $this->Html->link('<span class="arrow_left"></span><span class="text">'.$folder['Upload']['name'].'</span><span class="arrow_right"></span>','/admin/media'.$folder['Upload']['path'],array('escape'=>false,'style'=>'z-index:'.(count($detailPath)-$i+5).';','rev'=>'#Navigation|Folders|Files'));
			}else{
				echo $this->Html->tag('span',$folder['Upload']['name'],array('class'=>'currentFolder','style'=>'z-index:'.(count($detailPath)-$i+5).';'));
			}
		}
	}else{
		echo "<span class='homeCurrent'></span>";
	}
	?>
</div>