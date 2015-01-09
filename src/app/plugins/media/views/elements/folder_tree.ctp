<?php $spacer=(isset($spacer))? $spacer : '' ?>
<ul <?= isset($class)? $class : '' ?> >
<?php
foreach((array)$folders as $folder){
	$this->viewVars['infoFiles'][]=$this->Html->tag('div',
				$this->Html->image('/media/img/gtk-directory.png',array('alt'=>'')).
				$this->Html->tag('dl',
						$this->Html->tag('dt','[:filename:]').
						$this->Html->tag('dd',$folder['Upload']['name'])
					).
				$this->Html->tag('dl',
						$this->Html->tag('dt','[:file_created:]').
						$this->Html->tag('dd',$folder['Upload']['created'])
					).
				$this->Html->tag('dl',
						$this->Html->tag('dt','[:file_modified:]').
						$this->Html->tag('dd',$folder['Upload']['modified'])
					).
				$this->Html->tag('dl',
						$this->Html->tag('dt','[:file_size:]').
						$this->Html->tag('dd',$folder['Upload']['size'])
					).
				$this->Html->tag('dl',
						$this->Html->tag('dt','[:file_files:]').
						$this->Html->tag('dd',$folder['Upload']['size'])
					).
				$this->Html->tag('div',
						$this->Form->button('<span class="icon"></span>[:delete_file:]',array('class'=>'delete')).
						$this->Form->button('<span class="icon"></span>[:edit_file:]',array('class'=>'edit'))
					,array('class'=>'buttons'))
			,array('class'=>'inf','id'=>'Info_file'.$folder['Upload']['id']));
	$class=empty($folder['children'])? "empty ": false;
	$current=false;
	$classArrow="";
	if($folder['Upload']['path']=="/".$path){
		$class.="current";
		$current=true;
	}else if(strpos("/".$path,$folder['Upload']['path'])===0){
		$classArrow.="arrow_displayed";
		$current=true;
	}
	echo "<li class='$class'>";
	echo $this->Html->link($spacer.'<span class="arrow '.$classArrow.'"></span><span class="icon"></span>'.$folder['Upload']['name'],"/admin/media".$folder['Upload']['path'],array('escape'=>false,'class'=>$class,'rel'=>$folder['Upload']['id']));
	if(!empty($folder['children'])){
		echo $this->element('folder_tree',array('folders'=>$folder['children'],'class'=>($current)? 'class="displayed"': '','spacer'=>$spacer."<span class='spacer'></span>"));
	}
	echo "</li>";
}
?>
</ul>