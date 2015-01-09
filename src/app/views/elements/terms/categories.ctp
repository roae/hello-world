<?php /* @var $this View */?>
<div class="CategoriesControl panel">
	<div class="panel-title">[:categories-list:]</div>
	<div class="panel-content">
		<?php
		echo $this->I18n->input("Category",array(
			'options'=>$categories,
			'multiple'=>'checkbox',
			'label'=>false,
			'div'=>array('class'=>'CheckboxCategories')
		));
		?>
	</div>
</div>