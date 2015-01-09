<?php
if(isset($elements)||!empty($elements)){
	foreach($elements as $element=>$options){
		if(is_numeric($element)){
			$element=$options;
			$options=array();
		}
		echo $this->element($element,$options);
	}
}
?>