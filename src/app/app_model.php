<?php
class AppModel extends Model {

	var $actsAs = array(
		'I18n.Translate'=>array('notAllow'=>array('Aro','Aco','Group','User','Lang','Key','KeyMeaning','Field')),
		'Containable'
	);

	/**
	 * Metodo necesario para le funcionamiento del behavior I18n.Translate
	 * @param mixed $name
	 * @return boolean
	 */
	function hasField($name){
		return parent::hasField($name) || ($this->Behaviors->enabled("Translate") && $this->isTranslateField($name));
	}

	function setTablePrefix(){
		if(empty($this->tablePrefix)){
			$parentClass = Inflector::underscore(get_parent_class($this));
			$appmodel = 'app_model';
			if($parentClass != $appmodel && substr($parentClass,strlen($parentClass) - strlen($appmodel)) == $appmodel){
				$prefix = substr($parentClass,0,strlen($parentClass) - strlen($appmodel));
				if($prefix == "{$this->useTable}_"){
					$this->tablePrefix = null;
				}else{
					$this->tablePrefix = $prefix;
				}
			}
		}
	}

	function beforeSave(){
		if(!isset($this->data[$this->alias]['id'])){
			$this->data[$this->alias]['created_by']=Configure::read('loggedUser.User.id');
		}
		return true;
	}

	function isUnique($data, $fields){
		// check if the param contains multiple columns or a single one
		if (!is_array($fields)){
			$fields = array($fields);
		}

		// go trough all columns and get their values from the parameters
		foreach($fields as $key){
			$unique[$key] = $this->data[$this->name][$key];
		}

		// primary key value must be different from the posted value
		if (isset($this->data[$this->name][$this->primaryKey])){
			$unique[$this->primaryKey] = "<>" . $this->data[$this->name][$this->primaryKey];
		}

		// use the model's isUnique function to check the unique rule
		return parent::isUnique($unique, false);
	}

	/**
	 * Inicia una transacción
	 */
	function begin(){
		# se obtiene la coneccion a la BD (DataSoursce)
		$ds = $this->getDataSource();
		$ds->begin($this);
	}

	/**
	 * Commit de transacciones
	 */
	function commit(){
		$ds = $this->getDataSource();
		$ds->commit($this);
	}

	/**
	 * Rollback de transacciones
	 */
	function rollback(){
		$ds = $this->getDataSource();
		$ds->rollback($this);
	}

}
?>