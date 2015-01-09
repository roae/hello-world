<section class="content">
	<div class="img404">
		<?= $this->Html->image("error404.png"); ?>
	</div>
	<div class="inf">
		[:404_error_text:]
		<?php
		echo $this->Navigation->menu($defaultMenu['menu_footer'],array('id'=>'footermenu'));
		echo "[:404_search_form_text:]";
		echo $this->Form->create("Search",array('id'=>'Search','url'=>array('controller'=>'pages','action'=>'display','buscador'),'type'=>'get'));
			echo $this->I18n->input("search",array('name'=>'q','label'=>'[:search_404:]'));
			echo $this->Form->button("search",array('type'=>'submit'));
		echo $this->Form->end();
		?>
	</div>
	<section id="benefits">
		<div class="title">[:benefits_panel_title:]</div>
		<ul>
		<?php foreach(range(1,4) as $i): ?>
			<li class="<?php echo ($i%2==0) ? "even" : "odd" ?>">
				<div class="subtitle">[:benefit_title_<?= $i ?>:]</div>
				<?= $this->Html->image("timeshare-scam-victim-$i.png",array('class'=>'icon','alt'=>"[:benefit_$i-alt:]",'desc'=>preg_replace('/<[^<]+?>/','',$this->I18n->process("texto alternativo de la imagen del item: [:benefit_title_$i:] de la seccion: [:benefits_panel_title:]"))));?>
				[:benefit_text_<?= $i ?>:]
				<?php $this->I18n->addMissing("benefit_text_$i", preg_replace('/<[^<]+?>/','',$this->I18n->process("Texto del beneficio: [:benefit_title_$i:]")),'beneficios') ?>
				<span class="more"></span>
			</li>
		<?php endforeach;?>
		</ul>
		<div class="action">
			<?= $this->Html->link("[:action_to_contact:]",array('controller'=>'contacts','action'=>'add'),array('class'=>'btn_principal','title'=>'[:action_contact_title:]','desc'=>'Boton grande que aparece debajo de los testimoniales y los features'));?>
		</div>
	</section>

</section>