<?php
	echo $this->Form->button("[:poner_en_direccion:]",array('class'=>'btn btn-success','id'=>'sinc_direct','type'=>'button'));
	#echo $this->Form->button("[:poner_en_direccion:]",array('class'=>'btn btn-info','id'=>'sinc_direct','type'=>'button'));
	echo $this->Form->hidden('mark_lat') . $this->Form->hidden('mark_lng');
	echo $this->Form->hidden('map_lat') . $this->Form->hidden('map_lng') . $this->Form->hidden('map_zoom');
	echo $this->Form->hidden('sv_heading') . $this->Form->hidden('sv_pitch') . $this->Form->hidden('sv_zoom');
	echo $this->Form->hidden('sv_lat') . $this->Form->hidden('sv_lng');
	echo $this->Html->script(array('http://maps.google.com/maps/api/js?sensor=true','location_map'),array('inline'=>false));
	ob_start();
	echo $this->Html->div('canvas','',array('id' => 'map_canvas'));
	echo $this->Html->div('view','',array('id' => 'streetView'));
	echo $this->Html->div('street-view',ob_get_clean());
	echo $this->Form->button("[:sincronizar_street_view:]",array('class'=>'btn btn-info','id'=>'sincronizar','type'=>'button'));
	echo $this->I18n->input("street_view",array('type'=>'checkbox'));
?>