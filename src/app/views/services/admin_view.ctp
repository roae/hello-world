<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:System.admin_services_list:]',array('action' => 'index'));
$this->Html->addCrumb($record['Service']['name']);
echo $this->Ajax->div("data",array('class'=>'row-fluid item-view'));
?>
<div class="span8 offset2">
	<h2>[:info_Service:]</h2>
	<div class="span6">
		<?php
		echo $this->element("admin/view-field",array('label'=>'[:Service_name_input:]','data'=>$record['Service']['name']));
		echo $this->element("admin/view-field",array('label'=>'[:Service_icon:]','data'=>$this->Html->image($record['Icon']['url'])));
		echo $this->element("admin/view-field",array('label'=>'[:Service_description_input:]','data'=>$this->Xhtml->para("description",$record['Service']['description'])));
		?>
	</div>
	<div class="span6">
		<?php
		echo $this->element("admin/view-field",array('label'=>'[:Service_gallery:]','data'=>$this->element("admin/gallery",array('recordset'=>$record['Gallery']))));
		?>
	</div>
	<div class="tools span12">
		<div class="pull-left">
			<?php
			if($record['Service']['trash']){
				echo $this->Html->link("<i class='icon-ok'></i> [:restore:]",array('action'=>'restore',$record['Service']['id']),array('class'=>'btn noHistory','escape'=>false,'rev'=>'#data'));
			}else{
				echo $this->Html->link("<i class='icon-pencil'></i> [:edit:]",array('action'=>'edit',$record['Service']['id']),array('class'=>'btn btn_success','escape'=>false));
			}
			?>
		</div>
		<div class="pull-right">
			<?php
			if($record['Service']['trash']){
				echo $this->Html->link(
					"<i class='icon-remove-sign'></i> [:delete:]",
					array('action'=>'destroy',$record['Service']['id']),
					array('class'=>'btn_danger','data-confirm'=>'[:delete_service_name:]: '.h($record['Service']['name']).'?','escape'=>false)
				);
			}else if($trashAccess){
				echo $this->Html->link(
					"<i class='icon-remove-sign'></i> [:delete:]",
					array('action'=>'delete',$record['Service']['id']),
					array('class'=>'btn_danger action','rel'=>'[:delete_service_name:]: '.h($record['Service']['name']).'?','escape'=>false,'rev'=>'#data')
				);
			}else{
				echo $this->Html->link(
					"<i class='icon-trash'></i> [:delete:]",
					array('action'=>'delete',$record['Service']['id']),
					array('class'=>'btn_danger','data-confirm'=>'[:delete_service_name:]: '.h($record['Service']['name']).'?','escape'=>false)
				);
			}

			?>
		</div>
	</div>
</div>
<?php
echo $this->Ajax->divEnd("data");
?>

