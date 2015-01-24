<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_cities_list:]',array('action' => 'index'));
$this->Html->addCrumb($record['City']['name']);
echo $this->Ajax->div("data",array('class'=>'row-fluid item-view'));
?>
	<div class="span4 offset4">
		<h2>[:info_City:]</h2>
		<?= $this->element("admin/view-field",array('label'=>'[:City_name_input:]','data'=>$record['City']['name']));?>
		<div class="tools">
			<div class="pull-left">
				<?php
					if($record['City']['trash']){
						echo $this->Html->link("<i class='icon-ok'></i> [:restore:]",array('action'=>'restore',$record['City']['id']),array('class'=>'btn noHistory','escape'=>false,'rev'=>'#data'));
					}else{
						echo $this->Html->link("<i class='icon-pencil'></i> [:edit:]",array('action'=>'edit',$record['City']['id']),array('class'=>'btn btn_success','escape'=>false));
					}

				?>
			</div>
			<div class="pull-right">
				<?php
				if($record['City']['trash']){
					echo $this->Html->link(
						"<i class='icon-remove-sign'></i> [:delete:]",
						array('action'=>'destroy',$record['City']['id']),
						array('class'=>'btn_danger','data-confirm'=>'[:delete_city_name:]: '.h($record['City']['name']).'?','escape'=>false)
					);
				}else if($trashAccess){
					echo $this->Html->link(
						"<i class='icon-trash'></i> [:delete:]",
						array('action'=>'delete',$record['City']['id']),
						array('class'=>'btn_danger action','rel'=>'[:delete_city_name:]: '.h($record['City']['name']).'?','escape'=>false,'rev'=>'#data')
					);
				}else{
					echo $this->Html->link(
						"<i class='icon-trash'></i> [:delete:]",
						array('action'=>'delete',$record['City']['id']),
						array('class'=>'btn_danger','data-confirm'=>'[:delete_city_name:]: '.h($record['City']['name']).'?','escape'=>false)
					);
				}
				?>
			</div>
		</div>
	</div>
<?php
echo $this->Ajax->divEnd("data");
?>

