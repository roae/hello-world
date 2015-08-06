<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_locations_list:]',array('action' => 'index'));$this->Html->addCrumb('[:System.admin_smart_config:]');
echo $this->Form->create("Location",array('url'=>$this->Html->url()));
?>
<div class="contentForm">
	<div class="row-fluid">
		<div class="span5 offset2">
			<h2><?= h($record['Location']['name']) ?></h2>
			<?= $this->element("admin/view-field",array('label'=>'[:Location_smart_serialpos:]','data'=>$record['Location']['smart_serialpos']));?>
			<?= $this->element("admin/view-field",array('label'=>'[:Location_smart_user:]','data'=>$record['Location']['smart_user']));?>
			<?php
			echo $this->I18n->input("smart_passwd");
			/*if(!empty($record['Location']['smart_passwd'])){
				echo $this->I18n->input("smart_newpasswd");
			}*/
			$date = is_numeric($record['Location']['smart_lastlogin'])? date("Y-m-d H:i:s",$record['Location']['smart_lastlogin']) : "";
			?>
		</div>
		<div class="span3">
			<?= $this->element("admin/view-field",array('label'=>'[:Location_smart_lastserverkey:]','data'=>$record['Location']['smart_lastserverkey']));?>
			<?= $this->element("admin/view-field",array('label'=>'[:Location_smart_last_stan:]','data'=>$record['Location']['smart_last_stan']));?>
			<?= $this->element("admin/view-field",array('label'=>'[:Location_smart_current_stan:]','data'=>$record['Location']['smart_current_stan']));?>

			<?= $this->element("admin/view-field",array('label'=>'[:Location_smart_lastlogin:]','data'=>$date));?>
			<div class="help">
				<i class="icon-asterisk icon-3x"></i>
				[:help_smart_config_form:]
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
