<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_ads_groups_list:]',array('action' => 'index'));
$this->Html->addCrumb($record['Ad']['title']);
echo $this->Ajax->div("data",array('class'=>'row-fluid item-view'));
?>
<div class="span12">
	<h2>[:info_Ad:]</h2>
	<div class="span4">
		<?= $this->element("admin/view-field",array('label'=>'[:Ad_name_input:]','data'=>$record['Ad']['title']));?>
		<?= $this->element("admin/view-field",array('label'=>'[:Ad_type_input:]','data'=>$record['Ad']['type']));?>
		<?= $this->element("admin/view-field",array('label'=>'[:Ad_AdsGroup_name:]','data'=>$record['AdsGroup']['name']));?>
		<div class="tools">
			<div class="pull-left">
				<?php
				if($record['Ad']['trash']){
					echo $this->Html->link("<i class='icon-ok'></i> [:restore:]",array('action'=>'restore',$record['Ad']['id']),array('class'=>'btn noHistory','escape'=>false,'rev'=>'#data'));
				}else{
					echo $this->Html->link("<i class='icon-pencil'></i> [:edit:]",array('action'=>'edit',$record['Ad']['id']),array('class'=>'btn btn_success','escape'=>false));
				}
				?>
			</div>
			<div class="pull-right">
				<?php
				if($record['Ad']['trash']){
					echo $this->Html->link(
						"<i class='icon-remove-sign'></i> [:delete:]",
						array('action'=>'destroy',$record['Ad']['id']),
						array('class'=>'btn_danger','data-confirm'=>'[:delete_city_name:]: '.h($record['Ad']['title']).'?','escape'=>false)
					);
				}else if($trashAccess){
					echo $this->Html->link(
						"<i class='icon-trash'></i> [:delete:]",
						array('action'=>'delete',$record['Ad']['id']),
						array('class'=>'btn_danger action','rel'=>'[:delete_city_name:]: '.h($record['Ad']['title']).'?','escape'=>false,'rev'=>'#data')
					);
				}else{
					echo $this->Html->link(
						"<i class='icon-trash'></i> [:delete:]",
						array('action'=>'delete',$record['Ad']['id']),
						array('class'=>'btn_danger','data-confirm'=>'[:delete_city_name:]: '.h($record['Ad']['title']).'?','escape'=>false)
					);
				}
				?>
			</div>
		</div>
	</div>
	<div class="span8">
		<?php
		foreach(Configure::read("AdTypes") as $type){
			$width = $type == "Horizontal" ? "960px" : "235px";
			if($record[$type]['id']){
				echo $this->Html->image($record[$type]['url'],array('style'=>'width: 100%;display:block;max-width:'.$width));
			}
		}
		?>
	</div>


</div>
<?php
echo $this->Ajax->divEnd("data");
?>

