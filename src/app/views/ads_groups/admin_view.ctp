<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_ads_groups_list:]',array('action' => 'index'));
$this->Html->addCrumb($record['AdsGroup']['name']);
echo $this->Ajax->div("data",array('class'=>'row-fluid item-view'));
?>
	<div class="span4 offset4">
		<h2>[:info_AdsGroup:]</h2>
		<?= $this->element("admin/view-field",array('label'=>'[:AdsGroup_name_input:]','data'=>$record['AdsGroup']['name']));?>
		<div class="tools">
			<div class="pull-left">
				<?php
					if($record['AdsGroup']['trash']){
						echo $this->Html->link("<i class='icon-ok'></i> [:restore:]",array('action'=>'restore',$record['AdsGroup']['id']),array('class'=>'btn noHistory','escape'=>false,'rev'=>'#data'));
					}else{
						echo $this->Html->link("<i class='icon-pencil'></i> [:edit:]",array('action'=>'edit',$record['AdsGroup']['id']),array('class'=>'btn btn_success','escape'=>false));
					}

				?>
			</div>
			<div class="pull-right">
				<?php
				if($record['AdsGroup']['trash']){
					echo $this->Html->link(
						"<i class='icon-remove-sign'></i> [:delete:]",
						array('action'=>'destroy',$record['AdsGroup']['id']),
						array('class'=>'btn_danger','data-confirm'=>'[:delete_city_name:]: '.h($record['AdsGroup']['name']).'?','escape'=>false)
					);
				}else if($trashAccess){
					echo $this->Html->link(
						"<i class='icon-trash'></i> [:delete:]",
						array('action'=>'delete',$record['AdsGroup']['id']),
						array('class'=>'btn_danger action','rel'=>'[:delete_city_name:]: '.h($record['AdsGroup']['name']).'?','escape'=>false,'rev'=>'#data')
					);
				}else{
					echo $this->Html->link(
						"<i class='icon-trash'></i> [:delete:]",
						array('action'=>'delete',$record['AdsGroup']['id']),
						array('class'=>'btn_danger','data-confirm'=>'[:delete_city_name:]: '.h($record['AdsGroup']['name']).'?','escape'=>false)
					);
				}
				?>
			</div>
		</div>
	</div>
<?php
echo $this->Ajax->divEnd("data");
?>

