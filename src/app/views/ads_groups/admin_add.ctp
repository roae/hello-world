<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_ads_groups_list:]',array('action' => 'index'));$this->Html->addCrumb('[:System.admin_ads_groups_add:]');
echo $this->Form->create("AdsGroup");
?>
	<div class="contentForm">
		<div class="row-fluid">
			<div class="span4 offset4">
				<?php
				echo $this->I18n->input("name");
				?>
				<div class="help">
					<i class="icon-asterisk icon-3x"></i>
					[:help_city_form:]
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
			echo $this->Html->link('<i class="icon-remove"></i>[:cancel:]',array('action'=>'index'),array('class'=>'btn','escape'=>false));
			echo $this->Form->button('<i class="icon-ok"></i>[:save:]',array('type'=>'submit','class'=>'btn_success'));
			?>
		</div>
	</div>
<?= $this->Form->end(); ?>