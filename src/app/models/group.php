<?php
class Group extends AppModel {

	var $name = "Group";
	var $useTable = "groups";
	var $actsAs = array('Acl.Aclx'=>array('Aro','Aco'));
	#var $actsAs=array('Acl' => array('type' => 'requester'));
	
	var $belongsTo = array( );
	var $hasOne = array( );
	var $hasAndBelongsToMay = array( );
	var $hasMany = array( );
	var $displayField="name";

	var $validate = array(
		'name'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
			#'parent_id' => array('rule' => 'groupParentId','message' => '[:group_parent_required:]')
		)
	);

	function groupParentId(){
		if($this->hasAny()){
			return !empty($this->data[$this->alias]['parent_id']) && is_numeric($this->data[$this->alias]['parent_id']);
		}
		return true;
	}

	function parentNode(){
		return null;

		if($type == 'Aro'){
			if(!$this->id){
				return null;
			}
			$data = $this->read();
			if(!$data[$this->alias]['parent_id']){
				return null;
			}
			return array('model' => $this->name,'foreign_key' => $data[$this->alias]['parent_id']);
		}
		if(!$this->id){
			if(empty($this->data[$this->alias]['parent_id'])){
				return $this->{$axo}->find('first',array('conditions' => array("$axo.model" => 'Controller',"$axo.alias" => 'Grupos')));
			}
			return array('model' => $this->name,'foreign_key' => $this->data[$this->alias]['parent_id']);
		}

		$data = $this->read();
		if(!empty($data[$this->alias]['parent_id'])){
			return array('model' => $this->name,'foreign_key' => $data[$this->alias]['parent_id']);
		}
		
		return null;
	}

	function getPermiso($data = array()){
		$displayField = $this->Aro->Aco->displayField;
		$this->Aro->Aco->displayField = 'foreign_key';
		$permitidos = $this->Aro->Aco->find('list',array(
			'conditions' => array('Aco.model' => 'Grupo'),
			'joins' => array(
				array(
					'type' => 'INNER',
					'table' => 'aros_acos',
					'alias' => 'Permission',
					'conditions' => array('Permission.aco_id = Aco.id','Permission._read' => 1)
				),
				array(
					'type' => 'INNER',
					'table' => 'aros',
					'alias' => 'Aro',
					'conditions' => array('Aro.id = Permission.aro_id','Aro.alias' => 'Grupo.' . $data['Usuario']['grupo_id'])
				)
		)));
		$this->Aro->Aco->displayField = $displayField;
		return $permitidos;
	}

}
?>