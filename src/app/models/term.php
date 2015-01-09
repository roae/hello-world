<?php
class Term extends AppModel {

	var $name = "Term";
	var $useTable = "terms";
	var $belongsTo = array( );
	var $displayField = 'nombre';
	var $hasAndBelongsToMany = array( 'Article' );

	var $virtualFields = array(
		'cantidad'=>'count(distinct(Article.id))'
	);

	var $hasMany = array( );
	var $validate = array(
		'nombre' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
			'unica'=>array('rule'=>array('isUnique','nombre'),'allowEmpty','message'=>'[:term_already_exist:]')
		),
		'slug' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
			'unica'=>array('rule'=>array('isUnique','slug'),'allowEmpty','message'=>'[:slug_term_already_exist:]')
		)
	);

	var $actsAs = array(
		'Tree'
	);

	/**
	 * Se sobre escribio el metodo hasField para que el paginator ordenara por cantidad
	 * @param mixed $name
	 * @param bool  $checkVirtual
	 *
	 * @return bool|mixed
	 */
	function hasField($name, $checkVirtual = false){
		if($name == "cantidad"){
			return true;
		}
		return parent::hasField($name,$checkVirtual);
	}

	/*function beforeValidate($options = array()) {
		parent::beforeValidate($options);
		pr($this->data);
		if(empty($this->data['Term']['slug'])){
			$this->data['Term']['slug']=Inflector::slug($this->data['Term']['nombre']);
		}
		return true;
	}*/

}
?>