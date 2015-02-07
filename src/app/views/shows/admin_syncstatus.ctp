<?php /* @var $this View */
$this->Html->addCrumb('[:System.admin_syncstatus:]');

?>
	<div class="contentForm">
		<div class="row-fluid">
			<div class="span6 offset3"  id="Sync">
				<fieldset>
					<h1>[:syncstatus-title:]</h1>
					<?php
					$syncStatus = Cache::read("sync_billboard_status");
					if(!empty($syncStatus)){
						if(!$syncStatus['fail']){
							?>
							<div class="syncSuccess">
								<i class="icon"></i>
								[:syncstatus-success:]
							</div>
							<?
						}else{
							?>
							<div class="syncFail">
								<i class="icon"></i>
								[:syncstatus-fail:]
							</div>
							<?
						}
						?>
						<div class="date">
							[:Sync_date:]: <?= $syncStatus['date'] ?>
						</div>
						<?
					}
					echo $this->Html->link("[:syncbillboard:]",array('action'=>'sync'),array('escape'=>false,'class'=>'btn btn-info'));
					?>
				</fieldset>
			</div>
		</div>
	</div>