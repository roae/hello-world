<?php
/**
 * Class Article
 *
 * @property Category $Category
 */
class Article extends AppModel {

	var $name = "Article";
	var $useTable = "articles";
	var $actsAs = array(
		'Translate' => array( 'titulo','contenido','slug','keywords','description' ),
		'Media.Uploader' => array(
			'Foto' => array(
				'resize' => false,
				'copies' => array(
					'snipped' => array('width' => 100,'height'=>100,'image_ratio_crop' => true),
					'mini' => array('width' => 75,'height'=>75,'image_ratio_crop' => true),
					'medium' => array('width' => 280,'height'=>135,'image_ratio_crop' => true),
					'big' => array('width' => 775,'image_ratio_crop' => false,'image_ratio_y'=>true),
				),
				'limit' => 1,
				'required' => false,
				#'image_ratio_crop' => true,
				'allowed' => array('images'),
				'max_file_size'=>2,// MB
			)
		)
	);
	var $displayField="titulo";
	var $virtualFields=array(
		'comments'=>'count(distinct(Comment.id))','rating_count'=>'count(distinct(Rating.id))'
	);
	#var $belongsTo = array( "Category" );
	var $hasOne = array( );
	var $hasAndBelongsToMany = array(
		"Term",
		"Tag"=>array(
			'className'=>'Term',
			'joinTable'=>'articles_terms',
			'foreignKey'=>'article_id',
			'associationForeignKey'=>'term_id',
			'conditions'=>array('Tag.class'=>"Tag"),
			'unique'=>false,
			'with'=>'ArticlesTerm',
		),
		"Category"=>array(
			'className'=>'Term',
			'joinTable'=>'articles_terms',
			'foreignKey'=>'article_id',
			'associationForeignKey'=>'term_id',
			'conditions'=>array('Category.class'=>"Category"),
			'unique'=>false,
			'with'=>'ArticlesTerm',
		),
		'Related' => array(
			'className' => 'Article',
			'joinTable' => 'article_relationships',
			'foreignKey' => 'article_id',
			'associationForeignKey' => 'related_id',
			'limit' => '5',
		)
	);
	var $hasMany = array(
		'Comment'=>array(
			'className'=>'Comment',
			'foreignKey'=>'foreign_id',
			'conditions'=>array('Comment.class'=>'Article'),
			'dependent'=>true,
			'fields'=>array('Article.id','Article.slug','Article.titulo')
		),
	);

	var $validate = array(
		'titulo' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'contenido' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]' )
		),
		'slug' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]' )
		),
		'keywords' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]' )
		),
		'description' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]' )
		),
	);

	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		#$sql = "SELECT COUNT(DISTINCT(`Article`.`id`)) AS count FROM `articles` AS `Article` WHERE `Article`.`status` = 1";
		$db = $this->getDataSource();
		$sql = $db->buildStatement(
			array(
				'fields'     => array("COUNT(DISTINCT(`Article`.`id`)) AS count"),
				'table'      => $db->fullTableName($this),
				'alias'      => 'Article',
				'limit'      => null,
				'offset'     => null,
				'joins'      => array(),
				'conditions' => $conditions,
				'order'      => null,
				'group'      => null
			),
			$this
		);
		$this->recursive = $recursive;
		$results = $this->query($sql);
		return isset($results[0][0]['count']) ? $results[0][0]['count'] : 0;
	}


	function beforeSave($options){
		$this->begin();


		if(isset($this->data['Tag']) || isset($this->data['Category'])){

			# Se eleminan los terminos que pertenecen a este articulo de la tabla de la relacion
			if(isset($this->data['Article']['id']) && !empty($this->data['Article']['id'])){
				$conditions = array();
				$links = $this->ArticlesTerm->find('all', array(
					'conditions' => array('ArticlesTerm.article_id' => $this->data['Article']['id']),
					'recursive' => -1,
					'fields' => 'term_id'
				));
				$oldLinks = Set::extract($links, "{n}.ArticlesTerm.term_id");
				if(!empty($oldLinks)){
					$conditions['ArticlesTerm.term_id'] = $oldLinks;
					$conditions['ArticlesTerm.article_id'] = $this->data['Article']['id'];
					$this->ArticlesTerm->deleteAll($conditions);
				}
			}
			# Se agregan las etiquetas nuevas.
			foreach($this->data['Tag'] as $key => $tag){
				if(is_numeric($key)){
					$this->Term->create();
					$tag['slug'] = Inflector::slug($tag['nombre']);
					$tag['class'] = "Tag";
					$this->Term->save($tag);
					$this->data['Tag']['Tag'][] = $this->Term->id;
				}
			}
			$terms = $this->data['Tag']['Tag'];
			$this->data['Tag'] = array(
				'Tag'=>$terms
			);
		}

		return true;
	}

	function afterSave(){
		if(
			isset($this->data['Term']) ||
			isset($this->data['Article']['titulo_es_mx']) ||
			isset($this->data['Article']['contenido_es_mx'])
		){
			$this->generateRelated($this->data);
		}

		$this->commit();

	}


	function generateRelated($data){
		#pr($data);
		$terms = null;

		if(isset($data['Term']['Term'])){
			$terms = Set::classicExtract($data['Term']['Term'],"{n}.id");
		}else if(isset($data['Term']) && !empty($data['Term']) && is_array($data['Term'])){
			$terms = Set::classicExtract($data['Term'],"{n}.id");
		}

		if(empty($terms)){
			$terms=0;
		}else{
			$terms = sprintf("count(DISTINCT IF( `Term`.`term_id` IN (%s), `Term`.`term_id`, NULL )) * 1",implode($terms,","));
		}

		# Se obtienen todas las palabras con mas de 4 caracters del articulo
		$data['Article']['contenido'] = isset($data['Article']['contenido']) ? $data['Article']['contenido'] : $data['Article']['contenido_es_mx'];
		preg_match_all('/[a-zA-Z]{4,}/',strip_tags($data['Article']['contenido']),$words);

		$data['Article']['titulo'] = isset($data['Article']['titulo']) ? $data['Article']['titulo'] : $data['Article']['titulo_es_mx'];
		$matchTitulo = "(MATCH (Article.titulo) AGAINST ('".$data['Article']['titulo']."')) * 1";

		$matchContenido = sprintf("(MATCH (Article.contenido) AGAINST ('%s')) * 1",implode($words[0]," "));

		if(!isset($data['Article']['id'])){
			$data['Article']['id'] = $this->id;
		}

		# se obtienen todos los articulos
		$related = $this->find("all",array(
			'fields'=>array(
				'Article.id',
				"ROUND(0 + $matchTitulo + $matchContenido + $terms,2) AS score"
			),
			'joins'=>array(
				array(
					'type'=>'left',
					'table'=>'articles_terms',
					'alias'=>'Term',
					'conditions'=>array('Term.article_id = Article.id')
				)
			),
			'conditions'=>array(
				'Article.status'=>1,
				'Article.id <>'=>$data['Article']['id'],
			),
			'group' => 'Article.id HAVING score >= 50.0',
			'order'=>array('score DESC'),
			'limit'=>10
		));

		if(!empty($related)){
			$query = "REPLACE INTO article_relationships (article_id,related_id,score) VALUES ";
			foreach($related as $r){
				$query .= "({$data['Article']['id']},{$r['Article']['id']},{$r[0]['score']}),";
			}
			$query = substr($query, 0, -1);
			#pr($query);
			$this->query($query);
		}
	}

	function related($id){
		/*$this->id = $id;

		$this->contain(array("Related"));

		$r = $this->read(array('Article.id'));*/

		$r = $this->find("first",array(
			'conditions'=>array('Article.id'=>$id),
			'contain'=>array(
				'Related'=>array(
					'fields'=>array('Related.id','Related.titulo','Related.slug','Related.comments'),
					'joins'=>array(
						array(
							'type'=>'left',
							'table'=>'comments',
							'alias'=>'Comment',
							'conditions'=>array('Comment.foreign_id = Related.id','Comment.class = "Article"','Comment.status = 1')
						),
					),
					'group'=>array('Related.id'),
					'limit'=>4,
					'Foto'
				)
			)
		));
		return $r['Related'];
	}

	function neighbors($id){
		return array(
			'next'=>$this->find("first",array('conditions'=>array('Article.id >'=>$id),'fields'=>array('Article.id','Article.slug'))),
			'prev'=>$this->find("first",array('conditions'=>array('Article.id <'=>$id),'fields'=>array('Article.id','Article.slug')))
		);
	}

	function addView($id,$views){
		unset(
			$this->validate['titulo_'.Configure::read("I18n.Locale")],
			$this->validate['contenido_'.Configure::read("I18n.Locale")],
			$this->validate['slug_'.Configure::read("I18n.Locale")],
			$this->validate['keywords_'.Configure::read("I18n.Locale")],
			$this->validate['description_'.Configure::read("I18n.Locale")]
		);
		$this->save(array('Article'=>array(
			'id'=>$id,
			'views'=>$views+1
		)));
	}

}

?>