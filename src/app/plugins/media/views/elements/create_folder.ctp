<?= $this->Form->create("Upload",array('url'=>'/admin/media/add_folder/'.$path)) ?>
<span class="titlePanel">[:add_folder:]</span>
<?php echo $this->I18n->input('name');?>
<div class="buttons">
<?php
	echo $this->Form->button("[:create_folder:]",array('id'=>'MediSaveFolder','class'=>'buttonBar','title'=>'[:save_folder:]','desc'=>'Bonton guardar alta de un folder','type'=>'submit'));
?>
</div>
<?= $this->Form->end() ?>
<div class="creatingFolder"></div>