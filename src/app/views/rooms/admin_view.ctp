<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_rooms_list:]',array('action' => 'index'));
$this->Html->addCrumb($record['Room']['description']." - ".$record['Location']['name']);
echo $this->Ajax->div("data",array('class'=>'row-fluid item-view'));
?>
<div class="span4 offset4">
	<h2>[:info_Room:]</h2>
	<?php
	echo $this->element("admin/view-field",array('label'=>'[:Room_description_field:]','data'=>$record['Room']['description']));
	echo $this->element("admin/view-field",array('label'=>'[:Room_location_field:]','data'=>$record['Location']['name']));
	?>
	<div class="tools">
		<div class="pull-left">
			<?php
			if($record['Room']['trash']){
				echo $this->Html->link("<i class='icon-ok'></i> [:restore:]",array('action'=>'restore',$record['Room']['id']),array('class'=>'btn noHistory','escape'=>false,'rev'=>'#data'));
			}else{
				echo $this->Html->link("<i class='icon-pencil'></i> [:edit:]",array('action'=>'edit',$record['Room']['id']),array('class'=>'btn btn_success','escape'=>false));
			}
			?>
		</div>
		<div class="pull-right">
			<?php
			if($record['Room']['trash']){
				echo $this->Html->link(
					"<i class='icon-remove-sign'></i> [:delete:]",
					array('action'=>'destroy',$record['Room']['id']),
					array('class'=>'btn_danger','data-confirm'=>'[:delete_room_name:]: '.h($record['Room']['description']).'?','escape'=>false)
				);
			}else if($trashAccess){
				echo $this->Html->link(
					"<i class='icon-remove-sign'></i> [:delete:]",
					array('action'=>'delete',$record['Room']['id']),
					array('class'=>'btn_danger action','rel'=>'[:delete_room_name:]: '.h($record['Room']['description']).'?','escape'=>false,'rev'=>'#data')
				);
			}else{
				echo $this->Html->link(
					"<i class='icon-trash'></i> [:delete:]",
					array('action'=>'delete',$record['Room']['id']),
					array('class'=>'btn_danger','data-confirm'=>'[:delete_room_name:]: '.h($record['Room']['description']).'?','escape'=>false)
				);
			}

			?>
		</div>
	</div>
</div>
<?php
echo $this->Ajax->divEnd("data");
?>

