<?php
class   SettingsController extends AppController{
	var $name = "Settings";
	var $uses = array('Setting');

	/**
	 * Esta funcion funciona como un formulario en donde se muestran todas los settings para su edición
	 */
	function admin_index(){
		if(!empty($this->data)){
			#$this->Setting->set($this->data);
			if($this->Setting->saveAll($this->data['Setting'])){
				$this->Notifier->success("[:setting-saved-successfully:]");
			}
		}else{
			$data = $this->Setting->find("all");
			foreach($data as $record){
				$this->data['Setting'][] = $record['Setting'];
			}
		}
	}
}
?>