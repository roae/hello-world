<?php
$action = (!isset($action)) ? "add" : $action;
?>

<div class="tagForm">
	<h2>[:<?= $action."-".$this->params['class'] ?>:]</h2>
	<?php
	echo $this->Form->create("Term",array('url'=>array('controller'=>'terms','action'=>$action,'class'=>$this->params['class']),'class'=>'ajaxForm','data-update'=>'data','data-div'=>'ajaxForm'));
		if($action == "edit"){
			echo $this->Form->hidden("id");
		}
		echo $this->I18n->input('nombre',array('class'=>'slugger','after'=>$this->Html->tag("div","[:{$this->params['class']}-nombre-help:]",'help')));
		echo $this->I18n->input('slug',array('class'=>'slug','after'=>$this->Html->tag("div","[:{$this->params['class']}-slug-help:]",'help')));
		if($this->params['class'] == "Category"){
			echo $this->I18n->input("parent_id",array('after'=>$this->Html->tag("div","[:{$this->params['class']}-category-help:]"),'empty'=>'[:ninguna:]'));
		}
		echo $this->I18n->input('descripcion',array('class'=>'tiny-mini','after'=>$this->Html->tag("div","[:{$this->params['class']}-descripcion-help:]",'help')));
		?>
		<div class="buttons">
				<?php
				#echo $this->Form->button("<span class='icon-remove'></span>[:cancel:]",array('type'=>'reset','class'=>'btn_danger'));
				echo $this->Form->button("<span class='icon-plus'></span>[:{$action}_{$this->params['class']}_button:]",array('type'=>'submit','class'=>'btn_info'));
				?>
		</div>

	<?php echo $this->Form->end(); ?>
	<div class="helpBox">
		<span class="icon-asterisk icon-3x"></span>
		[:<?= $this->params['class'] ?>_help:]
	</div>
</div>