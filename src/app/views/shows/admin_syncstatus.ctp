<?php /* @var $this View */
$syncStatus = Cache::read("sync_billboard_status");
if($this->params['isAjax']){
	echo $this->Javascript->object($syncStatus);
}else{
	$this->Html->addCrumb('[:System.admin_syncstatus:]');

	$this->Html->script("admin/sync.min",false);

	echo $this->Html->scriptBlock("
		var SyncStatus = ".$this->Javascript->object($syncStatus).";
		var Locations = ".$this->Javascript->object($locations).";
	",array('inline'=>false));

?>
<h1>[:syncstatus-title:]</h1>
<div class="contentForm" id="Sync" ng-controller="SyncCtrl">

	<div class="row-fluid">
		<?php //if(!empty($syncStatus)){ ?>
			<div class="span6 generalStatus">
				<div ng-class="{syncFail:SyncStatus.fail && !SyncStatus.running,syncSuccess:!SyncStatus.fail && !SyncStatus.running,syncRunning:SyncStatus.running}">
					<i class="icon"></i>
					<div ng-if="SyncStatus.fail && !SyncStatus.running">[:syncstatus-fail:]</div>
					<div ng-if="!SyncStatus.fail && !SyncStatus.running">[:syncstatus-success:]</div>
					<div ng-if="SyncStatus.running">[:syncstatus-running:]</div>
				</div>
				<div class="date" ng-if="!SyncStatus.running">
					[:Sync_date:]: {{ SyncStatus.date }}
				</div>
				<div class="details"  ng-if="!SyncStatus.running">
					<div class="projections_not_found" ng-if="SyncStatus.projections_not_found">
						<h4>[:sync-projections-not-found-errors:]</h4>
						<ul>
							<li ng-repeat="(id,projection) in SyncStatus.projections_not_found"><h5>{{ id }}</h5> - <span>{{ projection }}</span></li>
						</ul>
					</div>
					<div class="projections_not_found" ng-if="SyncStatus.exec_errors">
						<h4>[:sync-projections-not-found-errors:]</h4>
						<ul>
							<li ng-repeat="error in SyncStatus.exec_errors"><span>{{ error }}</span></li>
						</ul>
					</div>
				</div>
				<button type="button" class="btn btn-primary" ng-click="sync('all')" ng-if="!SyncStatus.running"><i class="icon-refresh"></i> [:syncbillboard:]</button>
			</div>
			<div class="span6 locations">
				<div class="location" ng-class="{error: location.fail, success: !location.fail, running:location.running}"ng-repeat="location in SyncStatus.locations">
					<h4>{{ Locations[location.id] }}</h4>
					<span class="date">{{ location.date }}</span>
					<button type="button" class="btn btn_info" ng-click="sync(location.id)" ng-if="!SyncStatus.running"><i class="icon-refresh"></i> [:sync-location:]</button>
					<ul>
						<li ng-if="!location.connection">[:location-connection-fail:]</li>
						<li ng-if="!location.scheduled">[:location-scheduled-fail:]</li>
						<li ng-if="location.projections_not_found">[:location-projection-not-found:]</li>
					</ul>
				</div>
			</div>

	</div>
</div>
<?php
}
?>