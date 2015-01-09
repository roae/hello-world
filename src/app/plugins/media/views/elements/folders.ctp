	<?php $this->viewVars['infoFiles']=""; ?>
	<div id="MediaFolderTree">
		<ul class="root">
			<li><?= $this->Html->link('<span class="icon"></span>[:media_folder_home:]',"/admin/media",array('escape'=>false,'class'=>'MediaHome')); ?>
				<?= $this->element('folder_tree');?>
			</li>
		</ul>
	</div>
	<?php /*
	<div id="InfoFolderPane" class="panelFolder">
		//<span class="titleInfo">[:folder_info:]</span>
		<?= $this->viewVars['info'] ?>
	</div>
	<div id="AddFolderPanel" class="panelFolder">
		<?= $this->Form->create("Upload",array('url'=>'/admin/media/add_folder'.$path)) ?>
		<span class="titleInfo">[:add_folder:]</span>
		<?php echo $this->I18n->input('filename');?>
		<div class="buttons">
		<?php
			echo $this->Form->button("[:cancel:]",array('id'=>'MediaCancelFolder','class'=>'buttonBar','title'=>'[:cancel:]','desc'=>'Bonton de cancelar alta de un folder','type'=>'button'));
			echo $this->Form->button("[:add:]",array('id'=>'MediSaveFolder','class'=>'buttonBar','title'=>'[:save_folder:]','desc'=>'Bonton guardar alta de un folder','type'=>'submit'));
		?>
		</div>
		<?= $this->Form->end() ?>
	</div>
	<div class="toolBar">
		<?php
		echo $this->Form->button('<span class="icon"></span>',array('id'=>'MediaAddFolder','class'=>'toolButton add','title'=>'[:Media_add_folder:]','desc'=>'botton para agregar un nuevo folder en la barra de herramientas','type'=>'button'));
		echo $this->Form->button('<span class="icon"></span>',array('id'=>'MediaDeleteFolder','class'=>'toolButton delete','title'=>'[:Media_delte_folder:]','desc'=>'botton para eliminar un folder en la barra de herramientas','type'=>'button'));
		echo $this->Form->button('<span class="icon"></span>',array('id'=>'MediaHelpFolder','class'=>'toolButton help','title'=>'[:Media_help_folder:]','desc'=>'botton que muestra la ayuda de folders en la barra de herramientas','type'=>'button'));
		echo $this->Form->button('<span class="icon"></span>',array('id'=>'MediaRefreshFolder','class'=>'toolButton refresh','title'=>'[:Media_refresh_folder:]','desc'=>'botton actualizar la columna de folders en la barra de herramientas','type'=>'button'));
		echo $this->Form->button('<span class="icon"></span>',array('id'=>'MediaInfoFolder','class'=>'toolButton info','title'=>'[:Media_info_folder:]','desc'=>'botton para desplegar u ocultar la informacion del folder en la barra de herramientas','type'=>'button'));
		?>
	</div>
	 */ ?>