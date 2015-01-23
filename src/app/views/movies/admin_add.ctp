<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:System.admin_movies_list:]',array('action' => 'index'));$this->Html->addCrumb('[:System.admin_movies_add:]');
echo $this->Form->create("Movie");
?>
	<div class="contentForm">
		<div class="row-fluid">
			<div class="span4">
				<?php

				echo $this->I18n->input("title",array('div'=>array('class'=>'input text required span6')));
				echo $this->I18n->input("original_title",array('div'=>array('class'=>'input text span6')));
				echo $this->Html->div("span12",$this->Uploader->input('Poster',array('label'=>'[:Movie_Poster:]')));
				echo "<hr/>";
				echo $this->I18n->input("synopsis",array('div'=>array('class'=>'input textarea span12'),'class'=>'tiny-mini'));
				echo "<hr/>";
				echo $this->I18n->inputs(array(
					'genre'=>array('div'=>array('class'=>'input text span6')),
					'language'=>array('div'=>array('class'=>'input text span6')),
					'director'=>array('div'=>array('class'=>'input text span6')),
					'actors'=>array('div'=>array('class'=>'input text span6')),
					'music_director'=>array('div'=>array('class'=>'input text span6')),
					'photografy_director'=>array('div'=>array('class'=>'input text span6')),
					'year'=>array('div'=>array('class'=>'input text span6')),
					'nationality'=>array('div'=>array('class'=>'input text span6')),
					'website'=>array('div'=>array('class'=>'input text span6')),
					'clasification'=>array('div'=>array('class'=>'input text span6')),
					'website'=>array('div'=>array('class'=>'input text span6')),
					'trailer'=>array('div'=>array('class'=>'input text span6')),
					'duration'=>array('div'=>array('class'=>'input text span6')),
					'legend'=>'[:movie-general-info:]'
				));
				?>
			</div>

			<div class="span4">
				<?php
				echo $this->element("movies/projections");
				echo $this->Uploader->input('Gallery',array('label'=>'[:Movie_Gallery:]'));
				?>
			</div>
			<div class="span4">
				<?= $this->element("movies/locations"); ?>
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