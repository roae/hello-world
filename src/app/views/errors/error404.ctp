<section class="col-container">
	<div class="error404">
		<span>Error</span>
		404
	</div>
	<div class="inf">
		[:404_error_text:]
		<?php
		if(!isset($this->params['url']['mobile'])){
			echo $this->Html->link("[:ir-al-inicio:]","/",array('class'=>'btn'));
		}
		/*
		echo $this->Navigation->menu($defaultMenu['menu_footer'],array('id'=>'footermenu'));
		echo "[:404_search_form_text:]";
		echo $this->Form->create("Search",array('id'=>'Search','url'=>array('controller'=>'pages','action'=>'display','buscador'),'type'=>'get'));
			echo $this->I18n->input("search",array('name'=>'q','label'=>'[:search_404:]'));
			echo $this->Form->button("search",array('type'=>'submit'));
		echo $this->Form->end();
		*/
		?>
	</div>

</section>