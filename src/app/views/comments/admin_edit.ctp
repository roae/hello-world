<?php /* @var $this View */ ?>
<?php 
echo $this->Form->create("Comment",array('class'=>'FormEditComment'));
	echo $this->Form->hidden("id");
	echo $this->I18n->input("nombre");
	echo $this->I18n->input("message");
	echo $this->Html->tag("div",null,"buttons");
	echo $this->Form->button("Guardar",array('type'=>'submit','class'=>'button button_h'));
	echo $this->Form->button("Cancelar",array('type'=>'reset','class'=>'button cancel'));
	echo $This->Html->tag("/div");
echo $this->Form->end();
?>
