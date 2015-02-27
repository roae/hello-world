<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:System.admin_rooms_list:]',array('action' => 'index'));$this->Html->addCrumb('[:System.admin_rooms_add:]');
echo $this->Form->create("Room");
?>
	<div class="contentForm">
		<div class="row-fluid">
			<div class="span4 offset4">
				<?php
				echo $this->I18n->input("description",array('after'=>$this->Html->tag("span",'[:Room_description-help:]','input-help')));
				echo $this->I18n->input("location_id",array('empty'=>'[:select_location:]'));
				echo $this->I18n->input("room_type",array('empty'=>'[:select_room_type:]','options'=>Configure::read("RoomTypes")));
				?>
				<div class="help">
					<i class="icon-asterisk icon-3x"></i>
					[:help_room_form:]
				</div>
			</div>
		</div>
	</div>
	<div class="buttonPane">
		<div class="lButtons">
			<?php #echo $this->I18n->input('status',array('type'=>'radio','options'=>array(1=>'[:System.published:]',0=>'[:System.unpublished:]'),'legend'=>false,'value'=>1,'div'=>array('class'=>'radioButtons'),'fieldset'=>false)); ?>
		</div>
		<div class="rButtons">
			<?php
			echo $this->Html->link('<i class="icon-remove"></i>[:cancel:]',array('action'=>'index'),array('class'=>'btn_danger','escape'=>false));
			echo $this->Form->button('<i class="icon-ok"></i>[:save:]',array('type'=>'submit','class'=>'btn_success'));
			?>
		</div>
	</div>
<?= $this->Form->end(); ?>