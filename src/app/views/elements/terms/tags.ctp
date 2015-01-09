<?php /* @var $this View */
$this->Html->script("tag_control",array('inline'=>false));
?>
<div class="panel TagsControl">
	<div class="panel-title">[:select_tags:]</div>
	<div  class="panel-content">
		<div id="LabelTags">
			<?php
			foreach((array)$this->data['Tag'] as $key=>$tag){
				if(is_numeric($key) && !isset($tag['id'])){
					echo $this->Html->tag("label",
						$tag['nombre']
						,array('for'=>'TagTag'.$tag['id'],'id'=>'LabelTag'.$tag['id']));
				}
			}
			echo $this->I18n->input("Tag.nombre",array('label'=>false,'div'=>false,'placeholder'=>'[:nombre-tag:]'));
			?>
		</div>
		<?php
		echo $this->I18n->input("Tag",array('multiple'=>'checkbox','div'=>array('id'=>'checkboxTags'),'options'=>$tags));
		?>
	</div>
</div>