<?php
/**
 * Class Movie
 * @property $Projection Projection
 * @property $MovieLocation MovieLocation
 */
class Movie extends AppModel {

	var $name = "Movie";

	var $belongsTo = array( );
	var $hasOne = array( );
	var $hasAndBelongsToMany = array(
		#"Location",
	);
	var $hasMany = array(
		'Projection',
		'MovieLocation',
		'Show'
	);
	var $displayField="title";

	var $actsAs = array(
		'Media.Uploader' => array(
			'Poster' => array(
				'copies' => array(
					'mini' => array('width' => 95,'height'=>136,'image_ratio_crop' => true),
					'medium' => array('width' => 190,'height'=>272,'image_ratio_crop' => true),
					'big' => array('width' => 280,'height'=>544,'image_ratio_crop' => true,),
				),
				'limit' => 1,
				'required' => false,
				#'width'=>190,
				#'height'=>272,
				#'image_ratio_crop' => true,
				'allowed' => array('images'),
				'max_file_size'=>2,// MB
			),
			'Gallery'=>array(
				'copies' => array(
					'mini' => array('width' => 75,'height'=>75,'image_ratio_crop' => true),
					'big' => array('width' => 775,'image_ratio_crop' => false,'image_ratio_y'=>true),
				),
				'limit' => 10,
				'required' => false,
				'resize'=>false,
				#'image_ratio_crop' => true,
				'allowed' => array('images'),
				'max_file_size'=>2,// MB
			)
		)
	);

	var $validate = array(
		'title'=>array(
			'unico'=>array('rule'=>array('isUnique','title'),'message'=>'[:movie_already_existe:]'),
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]')
		)
	);

	function save($data = null, $validate = true, $fieldList = array()){
		# se copia el contenido de $this->data por que despues del save de Project $this->data cambiara.
		$data = $this->data;
		//pr($data);exit;

		# Booleano que indica que la transacción es exitosa.
		$saved = true;

		# Booleano que indica si los datos validaron o no.
		$validate = true;

		$this->begin();

		if( $this->validates() ){
			$saved = parent::save( $data['Movie'], false ) ? true : false;
		}else{
			$validate = false;
			if($saved) $saved =false;
		}

		$movie_id = $validate ? $this->id : null;

		$validationErrors = array();
		# Si la pelicula se guarda correctamente se agrega la relacion de complejos
		foreach(isset($data['MovieLocation'])? $data['MovieLocation']: array() as $index => $MovieLocation){
			if(isset($MovieLocation['location_id'])){
				$MovieLocation['movie_id'] = $movie_id;

				if(!isset($MovieLocation['premiere'])){
					$MovieLocation['premiere'] = null;
				}
				if(!isset($MovieLocation['presale'])){
					$MovieLocation['presale'] = null;
				}

				$this->MovieLocation->data = array();
				$this->MovieLocation->set($MovieLocation);
				$validations = $this->MovieLocation->validates();
				if($validations){
					if(!isset($MovieLocation['id'])){
						$this->MovieLocation->create();
					}
					#pr($MovieLocation);
					if($validate) $saved = $this->MovieLocation->save($MovieLocation,false) ? true : false;
				}else{
					$validate = $saved = false;
					$validationErrors[$index] = $this->MovieLocation->validationErrors;
				}
			}else if(isset($MovieLocation['id'])){
				$this->MovieLocation->delete($MovieLocation['id']);
			}
		}
		$this->MovieLocation->validationErrors = $validationErrors;

		# Se agrean las proyecciones de la pelicula
		$validationErrors = array();
		foreach ( isset( $data['Projection'] ) ? $data['Projection'] : array() as $index => $projection ) {
			$projection['movie_id'] = $movie_id;
			$this->Projection->set($projection);

			if( $this->Projection->validates() ){
				if( !isset( $projection['id'] ) ){
					#Si no existe el campo id en Projection es por que es un elemento nuevo
					$this->Projection->create();
				}
				#Se guarda la proyeccion si no ha habido ningun error de validación
				if( $validate ) $saved = $this->Projection->save( $projection, false ) ? true : false;
			}else{
				$validate = $saved = false;
				$validationErrors[$index] = $this->Projection->validationErrors;
			}
		}

		$this->Projection->validationErrors = $validationErrors;

		/**
		 * 	Se quitan los elementos eliminados por el ussuario
		 * 	que vienen en $this->data['deletes']
		 */

		if( isset( $data['deletes'] ) ){

			foreach((array) $data['deletes'] as $model => $deletes){
				$this->{$model}->delete($deletes);
			}
		}

		if($saved){
			$this->commit();
		}else{
			$this->rollback();
		}

		if( $validate && $saved ){
			/* Si todo el proceso fue exitoso */
			return 1;
		}else if($validate){
			/* Si valida pero no guarda es por algun error en el servidor */
			return -1;
		}

		/* Si no valida */
		return 0;
	}

	function beforeSave(){
		$this->data['Movie']['slug'] = Inflector::slug(low($this->data['Movie']['title']),"-");
		return true;
	}

}
?>