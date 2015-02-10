<h1 class="error">Errores en la sincronizaci&oacute;n de la cartelera </h1>
<small>Fecha: <?= date("d [:F:] Y H:i:s");?></small>
<?php
if(isset($errors['locations_connection'])){
	echo "<hr />";
	echo $this->Html->tag("h2","Problemas con la conecci&oacute;n de los siguientes complejos:","error");
	$li = "";
	foreach($errors['locations_connection'] as $location){
		$li.=$this->Html->tag("h3",$location['name']);
		$li.=$this->Html->tag("span",$location['vista_service_url']);
	}
	echo $this->Html->tag("ul",$li);
}

if(isset($errors['locations_not_scheduled'])){
	echo "<hr />";
	echo $this->Html->tag("h2","No se han agregado horarios de los siguientes complejos:","error");
	$li = "";
	foreach($errors['locations_not_scheduled'] as $location){
		$li.=$this->Html->tag("li",$location['name']);
	}
	echo $this->Html->tag("ul",$li);
}

if(isset($errors['projections_not_found'])){
	echo "<hr />";
	echo $this->Html->tag("h2","No se han asignado las siguientes pel&iacute;culas:","error");
	$li ="";
	foreach($errors['projections_not_found'] as $vista_code => $movie){
		//$projection = explode(">",$projection);
		$li .=$this->Html->tag("li","<strong>$vista_code</strong>: $movie");
	}
	echo $this->Html->tag("ul",$li);
}

if(isset($errors['exec'])){
	echo "<hr />";
	echo $this->Html->tag("h2","Errores en la ejecuci&oacute;n","error");
	$li ="";
	$li .=$this->Html->tag("li",$errors['exec']);
	echo $this->Html->tag("ul",$li);
}