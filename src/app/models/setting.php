<?php
class Setting extends AppModel{
	var $name = "Setting";
	var $use = "settings";

	function getConfig(){
		$settings = $this->find("all");
		$config = array();
		foreach($settings as $record){
			if(!empty($record['Setting']['name'])){
				$config[$record['Setting']['name']] = $record['Setting']['value'];
			}
		}
		return $config;
	}

}
?>