<?php

class L10nController extends I18nAppController{

	var $name = "L10n";
	var $uses = array('I18n.Key', 'I18n.KeyMeaning');

	function admin_edit(){
		$this->Session->write($this->Interpreter->sessionName, true);
		if(!empty($this->data['L10n'])){
			#debug($this->data);
			foreach($this->data['L10n'] as $key => $content){
				if(preg_match('/^([0-9]+)\-([a-zA-Z0-9_\-]+)/', $key, $matches)){
					$this->Key->query("REPLACE `" . $this->KeyMeaning->tablePrefix . $this->KeyMeaning->table . "` SET `content`='" . addslashes($content) . "', `key_id`=" . $matches[1] . ", `lang_id`='" . $this->data['Lang']['locale'] . "'");
					#echo "REPLACE `" . $this->KeyMeaning->tablePrefix . $this->KeyMeaning->table . "` SET `content`='" . addslashes($content) . "', `key_id`=" . $matches[1] . ", `lang_id`='" . $this->data['Lang']['locale'] . "'";
				}else{
					$keyID = $this->Key->query('SELECT `' . $this->Key->alias . '`.`id` FROM `' . $this->Key->tablePrefix . $this->Key->table . '` AS `' . $this->Key->alias . '` WHERE BINARY `key`="' . $key . '" LIMIT 1');
					if(empty($keyID)){
						$this->Key->create();
						$this->Key->set(array('key' => $key));
						$this->Key->save();
						$keyID = $this->Key->id;
					}else{
						$keyID = $keyID[0]['Key']['id'];
					}
					$this->Key->query("REPLACE `" . $this->KeyMeaning->tablePrefix . $this->KeyMeaning->table . "` SET `content`='" . addslashes($content) . "', `key_id`=" . $keyID . ", `lang_id`='" . $this->data['Lang']['locale'] . "'");
				}
			}
			if(!isset($this->params['named']['continue'])){
				$this->Session->delete($this->Interpreter->sessionName);
			}
		}/*else if(!isset($this->params['named']['continue'])){
				$this->Session->delete($this->Interpreter->sessionName);
			}
		}*/


		if(!empty($this->data) && !isset($this->params['named']['continue'])){
			$this->Session->delete($this->Interpreter->sessionName);
		}
		if(!$this->params['isAjax']){
			$this->redirect($this->referer());
		}
	}

	function admin_cancel(){
		$this->Session->delete($this->Interpreter->sessionName);
		$this->redirect($this->referer());
	}

	function interpret(){
		if(!isset($this->params['requested'])){
			$this->cakeError("error404");
		}
		return $this->Interpreter->process(ob_get_clean());
	}

}

?>