<?php /* @var $this View */
$this->Html->script("admin/ads.js",array('inline'=>false));
$this->Html->addCrumb('[:System.admin_ads_list:]',array('action' => 'index'));$this->Html->addCrumb('[:System.admin_ads_add:]');
echo $this->Form->create("Ad");
echo $this->Form->hidden("id");
?>
	<div class="contentForm">
		<div class="row-fluid">
			<div class="span6 offset3">
				<div class="span5">
					<?php
					echo $this->I18n->input("title");
					echo $this->I18n->input("ads_group_id",array('options'=>$ads_groups));
					echo $this->I18n->input("type",array('options'=>Configure::read("AdTypes")));
					echo $this->I18n->input("link");
					?>
				</div>
				<div class="span5 offset1 banners">
					<div class="help">
						<i class="icon-asterisk icon-3x"></i>
						[:help_city_form:]
					</div>
					<?php
					echo $this->Html->tag("div",$this->Uploader->input('Vertical',array('label'=>'Banner Vertical')),array('id'=>'VERTICAL','class'=>'banner'));
					echo $this->Html->tag("div",$this->Uploader->input('Horizontal',array('label'=>'Banner Horizontal')),array('id'=>'HORIZONTAL','class'=>'banner'));
					echo $this->Html->tag("div",$this->Uploader->input('VerticalMini',array('label'=>'Banner Vertical Mini')),array('id'=>'VERTICALMINI','class'=>'banner'));
					echo $this->Html->tag("div",$this->Uploader->input('Cuadro',array('label'=>'Banner Cuadrado')),array('id'=>'CUADRO','class'=>'banner'));
					?>
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
			echo $this->Html->link('<i class="icon-remove"></i>[:cancel:]',array('action'=>'index'),array('class'=>'btn','escape'=>false));
			echo $this->Form->button('<i class="icon-ok"></i>[:save:]',array('type'=>'submit','class'=>'btn_success'));
			?>
		</div>
	</div>
<?= $this->Form->end(); ?>