<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:System.admin_locations_list:]',array('action' => 'index'));
$this->Html->addCrumb($record['Location']['name']);
echo $this->Ajax->div("data",array('class'=>'row-fluid item-view'));
?>
<div class="contentForm">
	<div class="span8 offset2">
		<h2>[:info_Location:]</h2>

		<?php
		echo $this->element("view-field",array('label'=>'[:Location_name_input:]','data'=>$record['Location']['name']));
		echo $this->element("view-field",array('label'=>'[:Location_vista_code_input:]','data'=>$record['Location']['vista_code']));
		echo $this->element("view-field",array('label'=>'[:Location_vista_service_url_input:]','data'=>$record['Location']['vista_service_url']));
		echo $this->element("view-field",array('label'=>'[:Location_vista_phone_numbers_input:]','data'=>$record['Location']['phone_numbers']));
		echo $this->element("view-field",array('label'=>'[:Location_vista_state_input:]','data'=>$record['Location']['state']));
		echo $this->element("view-field",array('label'=>'[:Location_vista_zip_input:]','data'=>$record['Location']['zip']));
		echo $this->element("view-field",array('label'=>'[:Location_vista_street_input:]','data'=>$record['Location']['street']));
		echo $this->element("view-field",array('label'=>'[:Location_vista_neighborhood_input:]','data'=>$record['Location']['neighborhood']));
		echo $this->element("view-field",array('label'=>'[:Location_vista_interior_input:]','data'=>$record['Location']['interior']));
		echo $this->element("view-field",array('label'=>'[:Location_vista_outside_input:]','data'=>$record['Location']['outside']));

		?>
	</div>
</div>
<div class="buttonPane">
	<div class="pull-left">
		<?php
		if($record['Location']['trash']){
			echo $this->Html->link("<i class='icon-ok'></i> [:restore:]",array('action'=>'restore',$record['Location']['id']),array('class'=>'btn noHistory','escape'=>false,'rev'=>'#data'));
		}
		echo $this->Html->link("<i class='icon-pencil'></i> [:edit:]",array('action'=>'edit',$record['Location']['id']),array('class'=>'btn btn_success','escape'=>false));
		?>
	</div>
	<div class="pull-right">
		<?php
		echo $this->Html->link("<i class='icon-remove-sign'></i> [:delete:]",array('action'=>$record['Location']['trash'] ? 'destroy': 'delete',$record['Location']['id']),array('class'=>'btn_danger','rel'=>'[:delete_city_name:]: '.h($record['Location']['name']).'?','escape'=>false));
		?>
	</div>
</div>
<?php
echo $this->Ajax->divEnd("data");
?>

