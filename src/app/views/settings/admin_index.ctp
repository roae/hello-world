<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:System.admin_settings:]');
//pr(Configure::read("AppConfig"));
echo $this->Form->create("Setting");
?>
	<div class="contentForm" id="Settings">
		<h1>[:site-settings:]</h1>
		<div class="row-fluid">
			<div class="span8">
				<?php
				echo $this->Form->hidden("2.id");
				echo $this->I18n->input("2.value",array('type'=>'checkbox','label'=>'[:website-mantenance:]'));
				?>
				<fieldset>
					<legend>[:sync_billboard:]</legend>
				<?php
				/*foreach(range(1,23) as $hour){
					$hours[$hour] = $hour.":00";
				}*/
				/*echo $this->Form->hidden("0.id");
				echo $this->I18n->input("0.value",array('options'=>$hours,'label'=>'[:hora-sincronizacion-cartelera:]'));
				*/
				echo $this->Form->hidden("1.id");
				echo $this->I18n->input("1.value",array('type'=>'text','label'=>'[:email-error-cartelera:]','after'=>'<span class="help">[:email-error-cartelera-help:]</span>'));

				/*
				echo $this->Form->hidden("9.id");
				echo $this->I18n->input("9.value",array('type'=>'text','label'=>'[:sync_error_interval:]'));
				*/

				echo $this->Form->hidden("10.id");
				echo $this->I18n->input("10.value",array('type'=>'text','label'=>'[:sync_interval:]'));

				?>
				</fieldset>

				<fieldset>
					<legend>[:blog-config:]</legend>
					<?php
					echo $this->Form->hidden("3.id");
					echo $this->I18n->input("3.value",array('type'=>'text','label'=>'[:post-per-page:]'));

					echo $this->Form->hidden("4.id");
					echo $this->I18n->input("4.value",array('type'=>'checkbox','label'=>'[:related-posts:]'));

					echo $this->Form->hidden("5.id");
					echo $this->I18n->input("5.value",array('type'=>'text','label'=>'[:related-posts-count:]'));

					?>
				</fieldset>

				<fieldset>
					<legend>[:contact-config:]</legend>
					<?php
					echo $this->Form->hidden("6.id");
					echo $this->I18n->input("6.value",array('type'=>'text','label'=>'[:contact-email:]','after'=>'<span class="help">[:contact-email-help:]</span>'));

					echo $this->Form->hidden("7.id");
					echo $this->I18n->input("7.value",array('type'=>'text','label'=>'[:contact-email-cc:]','after'=>'<span class="help">[:contact-email-cc-help:]</span>'));

					?>
				</fieldset>
				<fieldset>
					<legend>[:buys_config:]</legend>
					<?php
					echo $this->Form->hidden("13.id");
					echo $this->I18n->input("13.value",array('type'=>'text','label'=>'[:buy_remaining_time:]'));

					echo $this->Form->hidden("14.id");
					echo $this->I18n->input("14.value",array('type'=>'text','label'=>'[:buy_bcc_confirmation:]'));
					?>
				</fieldset>
				<fieldset>
					<legend>[:smart-connector-config:]</legend>
					<?php
					echo $this->Form->hidden("11.id");
					echo $this->I18n->input("11.value",array('type'=>'text','label'=>'[:smart-url:]'));

					echo $this->Form->hidden("12.id");
					echo $this->I18n->input("12.value",array('type'=>'text','label'=>'[:smart-clientID:]'));

					/*echo $this->Form->hidden("13.id");
					echo $this->I18n->input("13.value",array('type'=>'text','label'=>'[:smart-clientPOS:]'));

					echo $this->Form->hidden("14.id");
					echo $this->I18n->input("14.value",array('type'=>'text','label'=>'[:smart-user:]'));

					echo $this->Form->hidden("15.id");
					echo $this->I18n->input("15.value",array('type'=>'text','label'=>'[:smart-passwd:]'));

					echo $this->Form->hidden("16.id");
					echo $this->I18n->input("16.value",array('type'=>'text','label'=>'[:smart-last_stan:]'));

					echo $this->Form->hidden("17.id");
					echo $this->I18n->input("17.value",array('type'=>'text','label'=>'[:smart-current_stan:]','after'=>'<span class="help">[:smart-current_stan-help:]</span>'));

					echo $this->Form->hidden("18.id");
					echo $this->I18n->input("18.value",array('type'=>'text','label'=>'[:smart-login_date:]'));

					echo $this->Form->hidden("19.id");
					echo $this->I18n->input("19.value",array('type'=>'text','label'=>'[:smart-lastServerKey:]','after'=>'<span class="help">[:smart-lastserverkey:]</span>'));*/
					?>
					<a href="/php-tail/Log.php" target="popup" class="btn_info" onClick="window.open(this.href, this.target, 'width=1000,height=500'); return false;">SmartConnector Log</a>

				</fieldset>
			</div>
		</div>
	</div>
	<div class="buttonPane">
		<div class="lButtons">
			<?php #echo $this->I18n->input('status',array('type'=>'radio','options'=>array(1=>'[:System.published:]',0=>'[:System.unpublished:]'),'legend'=>false,'value'=>1,'div'=>array('class'=>'radioButtons'),'fieldset'=>false)); ?>
		</div>
		<div class="rButtons">
			<?php
			echo $this->Form->button('<i class="icon-ok"></i>[:save:]',array('type'=>'submit','class'=>'btn_success'));
			?>
		</div>
	</div>
<?= $this->Form->end(); ?>