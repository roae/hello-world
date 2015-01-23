<?php /* @var $this View */
$this->Html->script('ext/tiny_mce/jquery.tinymce',array('inline'=>false));
$this->Html->script('tiny',array('inline'=>false));
$this->Html->addCrumb('[:System.admin_movies_list:]',array('action' => 'index'));
$this->Html->addCrumb($record['Movie']['title']);
echo $this->Ajax->div("data",array('class'=>'row-fluid item-view'));
?>
<div class="span12">
	<h2>[:info_Movie:]</h2>
	<div class="span2">
		<?
		echo $this->element("admin/view-field",array('label'=>'[:Movie_poster:]','data'=>$this->Html->image($record['Poster']['medium'],array('class'=>'poster'))));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_genre_input:]','data'=>$record['Movie']['genre']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_year_input:]','data'=>$record['Movie']['year']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_clasification_input:]','data'=>$record['Movie']['clasification']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_duration_input:]','data'=>$record['Movie']['duration']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_nationality_input:]','data'=>$record['Movie']['nationality']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_language_input:]','data'=>$record['Movie']['language']));
		?>
	</div>
	<div class="span5">
		<?php
		echo $this->element("admin/view-field",array('label'=>'[:Movie_title_input:]','data'=>$record['Movie']['title']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_original_title_input:]','data'=>$record['Movie']['original_title']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_synopsis_input:]','data'=>$this->Xhtml->para("description",$record['Movie']['synopsis'])));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_director_input:]','data'=>$record['Movie']['director']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_photografy_director_input:]','data'=>$record['Movie']['photografy_director']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_music_director_input:]','data'=>$record['Movie']['music_director']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_actors_input:]','data'=>$record['Movie']['actors']));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_website_input:]','data'=>$this->Html->link($record['Movie']['website'],$record['Movie']['website'],array('target'=>'_blank'))));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_trailer_input:]','data'=>$this->Html->link($record['Movie']['trailer'],$record['Movie']['trailer'],array('target'=>'_blank'))));
		if(!empty($record['Gallery'])){
			echo $this->element("admin/view-field",array('label'=>'[:Movie_gallery:]','data'=>$this->element("admin/gallery",array('recordset'=>$record['Gallery']))));
		}

		?>
	</div>
	<div class="span5">
		<?php
		echo $this->element("admin/view-field",array('label'=>'[:Movie_projections:]','data'=>$this->element("movies/view_projections",array('projections'=>$record['Projection']))));
		echo $this->element("admin/view-field",array('label'=>'[:Movie_locations:]','data'=>$this->element("movies/view_locations",array('locations'=>$record['MovieLocation']))));

		?>
	</div>
	<div class="tools span12">
		<div class="pull-left">
			<?php
			if($record['Movie']['trash']){
				echo $this->Html->link("<i class='icon-ok'></i> [:restore:]",array('action'=>'restore',$record['Movie']['id']),array('class'=>'btn noHistory','escape'=>false,'rev'=>'#data'));
			}else{
				echo $this->Html->link("<i class='icon-pencil'></i> [:edit:]",array('action'=>'edit',$record['Movie']['id']),array('class'=>'btn btn_success','escape'=>false));
			}
			?>
		</div>
		<div class="pull-right">
			<?php
			if($record['Movie']['trash']){
				echo $this->Html->link(
					"<i class='icon-remove-sign'></i> [:delete:]",
					array('action'=>'destroy',$record['Movie']['id']),
					array('class'=>'btn_danger','data-confirm'=>'[:delete_movie_name:]: '.h($record['Movie']['title']).'?','escape'=>false)
				);
			}else if($trashAccess){
				echo $this->Html->link(
					"<i class='icon-remove-sign'></i> [:delete:]",
					array('action'=>'delete',$record['Movie']['id']),
					array('class'=>'btn_danger action','rel'=>'[:delete_movie_name:]: '.h($record['Movie']['title']).'?','escape'=>false,'rev'=>'#data')
				);
			}else{
				echo $this->Html->link(
					"<i class='icon-trash'></i> [:delete:]",
					array('action'=>'delete',$record['Movie']['id']),
					array('class'=>'btn_danger','data-confirm'=>'[:delete_movie_name:]: '.h($record['Movie']['title']).'?','escape'=>false)
				);
			}

			?>
		</div>
	</div>
</div>
<?php
echo $this->Ajax->divEnd("data");
?>

