<?php
$this->Html->css("/media/css/mediaui",'stylesheet',array('inline'=>false));
$this->Html->script("/media/js/ui_control",array('inline'=>false));
#echo $this->Html->script(array('/media/js/jquery.uploadify.v2.1.4','/media/js/swfobject','/media/js/jquery.gritter.min'));
//echo $this ->Ajax->div("mediaInterface");

?>
<div id="mediaInterface">
	<?php
	echo $this->Ajax->div("Navigation");
		$this->I18n->start();
		echo $this->element('navigation');
		$this->I18n->end();
	echo $this->Ajax->divEnd("Navigation");

	echo $this->Ajax->div('Folders');
	$this->I18n->start();
		echo $this->element("folders");
	$this->I18n->end();
	echo $this->Ajax->divEnd("Folders");
	?>
	<?php /*
	<div id="FormBar" class="showBar">
		<?= $this->Ajax->div("CreateFolder"); ?>
			<?= $this->element('create_folder'); ?>
		<?= $this->Ajax->divEnd("CreateFolder"); ?>
		<div id="AddFiles">
			<span class="titlePanel">[:add_files:]</span>
			<?= $this->element('uploader'); ?>
		</div>
	</div>
	<?php */
	echo $this->Ajax->div("Files",array());
		$this->I18n->start();
		echo $this->element('info_file');
		echo $this->element('thumbnails');
		echo $this->Form->create('Upload',array('url'=>array('controller'=>'files','action'=>'delete_file','plugin'=>'media','admin'=>'true'),'id'=>'deleteForm'));
		echo $this->Form->input("url");
		echo $this->Form->end();
		$this->I18n->end();
	echo $this->Ajax->divEnd("Files");
	?>

	<div class="toolBar">
		<div class="folderTools">
		</div>
		<div class="filesTools">
			<?php
			echo $this->Form->button('<span class="icon"></span>',array('id'=>'MediaAddFile','class'=>'toolButton add','title'=>'[:Media_add_file:]','desc'=>'botton para agregar un nuevo archivo en la barra de herramientas','type'=>'button'));
			echo $this->Form->button('<span class="icon"></span>',array('id'=>'MediaDeleteFile','class'=>'toolButton delete','title'=>'[:Media_delte_file:]','desc'=>'botton para eliminar un archivo en la barra de herramientas','type'=>'button'));
			echo $this->Form->button('<span class="icon"></span>',array('id'=>'MediaInfoFile','class'=>'toolButton info','title'=>'[:Media_info:]','desc'=>'botton botton de informacion en la barra de herramientas','type'=>'button'));
			echo $this->Form->button('<span class="icon"></span>',array('id'=>'MediaHelp','class'=>'toolButton help','title'=>'[:Media_help:]','desc'=>'botton botton de ayuda en la barra de herramientas','type'=>'button'));
			?>
		</div>
	</div>
</div>
<div class="loadingMedia"></div>
