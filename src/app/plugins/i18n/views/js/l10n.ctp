var __L10n={
<?php
	$tabs=Cache::read("I18n.Interpreter.MissingJs");
	$tabKeys="";
	if(!empty($tabs)){
		foreach((array) $tabs as $tab){
			$tabKeys.="[:".implode(':][[-keys-]][:',array_keys((array)$tab)).":][[-tabs-]]"; # se une todo el arreglo en una cadena para traducir todo junto y asi solo se hace un query para todas las clavez
		}
		$tabKeys=$this->I18n->process($tabKeys); # se interpretan las claves de edicion

		# se forma un arreglo con las clavez ya interpretadas
		$keyContents=explode('[[-tabs-]]',$tabKeys);
		foreach($keyContents as $index=>$tab){
			$keyContents[$index]=explode('[[-keys-]]',$tab);
		}
		#$this->log($keyContents,'debug');
		$_keys=array();
		$kt=0;
		foreach((array)$tabs as $keys){
			$i=0;
			foreach($keys as $key=>$desc){
				$_keys[]='"'.$key.'": unescape(decodeURIComponent("'.rawurlencode($keyContents[$kt][$i]).'"))';
				$i++;
			}
			$kt++;
		}
		echo implode(',', $_keys);
	}
	#$this->log($this->element('sql_dump'),'debug');
?>
};