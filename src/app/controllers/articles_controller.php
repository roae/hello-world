<?php
/**
 * Class ArticlesController
 *
 * @property Article $Article
 */
class ArticlesController extends AppController{

	var $name = "Articles";
	var $uses = array("Article");
	var $ribbonBar = array(
		'title' => '{image:img} [:admin_Articles_title:]',
		'options' => array(
			'image' => array('src' => 'icons/48/help.png', 'alt' => 'Articles')
		),
		'links' => array()
	);
	var $paginate = array('limit' => 20,'contain'=>array('Foto'),'order'=>'Article.id DESC');

	var $helpers = array('Media.Uploader' => 'Form');

	var $components= array("Captcha","Email");

	function beforeFilter(){
		parent::beforeFilter();

		# Se obtienen las acciones a las que el usuario tiene permiso de este controller
		$this->access=array(
			'trash'=>$this->__checkAccessUrl(array('controller'=>'articles','action'=>'trash','admin'=>true,'plugin'=>false)),
			'restore'=>$this->__checkAccessUrl(array('controller'=>'articles','action'=>'restore','admin'=>true,'plugin'=>false)),
			'destroy'=>$this->__checkAccessUrl(array('controller'=>'articles','action'=>'destroy','admin'=>true,'plugin'=>false)),
			'delete'=>$this->__checkAccessUrl(array('controller'=>'articles','action'=>'destroy','admin'=>true,'plugin'=>false)),
		);
		$this->set("trashAccess",$this->access['trash']);
		$this->set("restoreAccess",$this->access['restore']);
		$this->set("destroyAccess",$this->access['destroy']);
		$this->set("deleteAccess",$this->access['delete']);

	}

	function admin_index(){
		$conditions = array();
		if(isset($this->data['Article']['search'])){
			if(is_numeric($this->data['Article']['search'])){
				$conditions=array('Article.id'=>$this->data['Article']['search']);
			}else{
				$conditions=array("Article.titulo like"=>"%{$this->data['Article']['search']}%");
			}
		}
		$this->set("recordset", $this->paginate("Article",am(array('Article.trash'=>0),$conditions)));
	}

	function admin_add(){
		if(!empty($this->data)){
			$this->Article->set($this->data);
			if($this->Article->validates()){
				if($this->Article->save()){
					$this->Notifier->success("[:Article_saved_successfully:]");
					$this->redirect("/admin/articles/index/");
				}else{
					$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
				}
			}else{
				//pr($this->Article->invalidFields());
				$this->Notifier->error("[:some_fields_invalid:]");
			}
		}

		$this->set("tags",$this->Article->Term->find("list",array('conditions'=>array('Term.class'=>'Tag'))));
		$this->set("categories",$this->Article->Term->generatetreelist(array('Term.class'=>'Category'),null,null,"-- "));
	}

	function admin_edit($id=null){
		#pr($this->data);
		if(!empty($id)){
			if(!empty($this->data)){
				$this->Article->set($this->data);
				if($this->Article->validates()){
					if($this->Article->save($this->data, false)){
						$this->Notifier->success("[:Article_updated_successfully:]");
						$this->redirect(array('action'=>'index'));
					}else{
						$this->Article->rollback();
						$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
					}
				}else{
					$this->Notifier->error("[:some_fields_invalid:]");
				}
			}else{
				$this->Article->translate("*");
				$this->Article->contain(array('Foto',"Tag","Category"));
				$this->data = $this->Article->read(null, $id);
			}
		}

		$this->set("tags",$this->Article->Term->find("list",array('conditions'=>array('Term.class'=>'Tag'))));
		$this->set("categories",$this->Article->Term->generatetreelist(array('Term.class'=>'Category'),null,null,"-- "));
	}

	function admin_view($id){
		if(!empty($id)){
			$this->Article->contain(array(
				'Foto',
				'Tag',
				'Category'
			));
			$record = $this->Article->read(null, $id);
			if(empty($record) || ($record['Article']['trash'] && !$this->access['trash'])){
				$this->Notifier->error("[:Article_not_found:]");
				$this->redirect(array('action'=>'index'));
			}
			$this->set("record",$record);
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Article_id:]"));
		}
	}

	function admin_status($state=null, $id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect("/admin/articles/index/");
			}
			if(!empty($state) || $state == 0){
				if($this->Article->updateAll(array('Article.status' => $state), array('Article.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:Article_publish_successfully:]" : "[:Article_unpublish_successfully:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Article_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect("/admin/articles/index");
		}
	}

	function admin_delete($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(Router::parse($this->referer()));
			}
			if($this->Article->updateAll(array('Article.trash' => 1), array('Article.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Article_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Article_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$referer = Router::parse($this->referer());
			if($referer['action'] == 'edit'){
				$this->redirect(array('action'=>'index'));
			}
			$this->redirect($this->referer());
		}
	}

	function admin_trash(){
		$conditions=array('Article.trash'=>1);
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=am($conditions,array('Article.id'=>$this->data['Xpagin']['search']));
			}else{
				$conditions=am($conditions,array("Article.name like"=>"%{$this->data['Xpagin']['search']}%"));
			}
		}
		$this->set("recordset",$this->paginate("Article",$conditions));
	}

	function admin_restore($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(array('action'=>'index'));
			}
			if($this->Article->updateAll(array('Article.trash' => 0), array('Article.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Article_restored_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Article_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect($this->referer());
		}
	}

	function admin_destroy($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(array('action'=>'index'));
			}
			if($this->Article->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Article_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Article_id_add:]"));
		}
		$referer = Router::parse($this->referer());
		if($referer['action'] == 'view'){
			$this->redirect(array('action'=>'trash'));
		}
		$this->redirect($this->referer());
	}


	function index(){
		if(!empty($this->params['pass']) && !isset($this->params['tag_slug']) && !isset($this->params['category_slug'])){
			$this->cakeError('error404');
		}

		$conditions = array('Article.status'=>1);
		if(isset($this->params['tag_slug']) && !empty($this->params['tag_slug'])){
			$tag = $this->Article->Term->find("first",array('conditions'=>array('Term.slug'=>$this->params['tag_slug'])));
			$sql = "SELECT `article_id` FROM `articles_terms` AS `ArticlesTerm`
					WHERE `term_id` = ".$tag['Term']['id'];
			$conditions = am($conditions,array("Article.id IN($sql)"));
			$this->set("tag",$tag);
		}

		if(isset($this->params['category_slug']) && !empty($this->params['category_slug'])){
			$category = $this->Article->Term->find("first",array('conditions'=>array('Term.slug'=>$this->params['category_slug'])));
			$sql = "SELECT `article_id` FROM `articles_terms` AS `ArticlesTerm`
					WHERE `term_id` = ".$category['Term']['id'];
			$conditions = am($conditions,array("Article.id IN($sql)"));
			$this->set("category",$category);
		}

		$this->paginate=array(
			'fields'=>array('Article.id','Article.autor','Article.created','Article.titulo','Article.contenido','Article.slug','Article.description',"Article.comments"),
			'limit'=>1,
			'contain'=>array('Foto', 'Tag', 'Category'),
			'conditions'=>$conditions,
			'joins'=>array(
				array(
					'type'=>'left',
					'table'=>'comments',
					'alias'=>'Comment',
					'conditions'=>array('Comment.foreign_id = Article.id','Comment.class = "Article"','Comment.status = 1')
				),
			),
			'group'=>array('Article.id'),
			'order'=>array('Article.created'=>'desc')
		);
		$this->set("recordset", $this->paginate("Article"));
		$page=(isset($this->params['named']['page'])) ? "| ".$this->params['named']['page'] : "";
		$sort=(isset($this->params['named']['direction']) && $this->params['named']['direction']=="desc")? "[:sort_newest:]" : "[:sort_oldest:]";
		$direction=(isset($this->params['named']['direction'])) ? "| ".$sort : "";
		$this->pageTitle.=" $page $direction";
		$this->pageDescription.=" $page $direction";
	}

	function view(){
		#$this->cacheAction="1 day";
		//$this->Article->contain(array("Foto","Tag",'Category'));
		$query=array(
			'fields'=>array('Article.id','Article.autor','Article.created','Article.titulo','Article.contenido','Article.slug','Article.description',"Article.views",'Article.comments',"Article.keywords"),
			'conditions'=>array('Article.id'=>$this->params['id'],'Article.status'=>1),
			'joins'=>array(
				array(
					'type'=>'left',
					'table'=>'comments',
					'alias'=>'Comment',
					'conditions'=>array('Comment.foreign_id = Article.id','Comment.class = "Article"','Comment.status = 1')
				),
			),
			'contain'=>array(
				'Related' => array(
					'fields'=>array('Related.id','Related.titulo','Related.slug','Related.comments','Related.created'),
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
				),
				'Foto',
				'Tag',
				'Category'
			),
			'group'=>array('Article.id')
		);
		if($record=$this->Article->find("first",$query)){
			$this->set("neighbors",$this->Article->neighbors($record['Article']['id']));

			$this->Article->addView($record['Article']['id'],$record['Article']['views']);

			//pr($this->params['slug']);
			if(isset($this->params['named']['sort'])){
				unset($this->params['named']['sort'],$this->params['named']['direction']);
				$this->redirect(am(array('action'=>'view','id'=>$this->params['id'],'slug'=>$this->params['slug']),$this->params['named']),301);
			}
			if(preg_replace('/\/$/','',$this->params['slug']) == $record['Article']['slug']){
				$this->set('record',$record);
				if(!empty($this->data)){
					$this->data['Comment']['ip']=$_SERVER['REMOTE_ADDR'];
					if($this->Captcha->check($this->data['Comment']['captcha'],'comment') & $this->Article->Comment->save($this->data)){
						$this->Email->to = Configure::read('Contact');
						$this->Email->bcc = Configure::read('Contact_bcc');
						$this->Email->subject = 'Comentario en el articulo '.$record['Article']['titulo'];
						$this->Email->from = Configure::read('Contact');
						$this->Email->sendAs = 'html';
						$this->Email->template = 'comment';
						//$this->set('titulo', $record['Hotel']['titulo']);
						//$this->data['Comment']['ip'] = $_SERVER['REMOTE_ADDR'];
						$this->set("comments", $this->Article->Comment->getPath($this->Article->Comment->id));
						$this->set($record['Article']);
						$this->data['Comment']['id']=$this->Article->Comment->id;
						$this->set("data",$this->data);
						$this->Email->send();
						$this->data['Comment']=array();
						$this->Notifier->success($this->Interpreter->process("[:comment_posted:]"));
						$this->redirect(array('action'=>'view','id'=>$record['Article']['id'],'slug'=>$record['Article']['slug']));
					}else{
						$this->Article->Comment->invalidate('captcha','[:captcha_error:]');
						$this->Notifier->error($this->Interpreter->process("[:algunos_campos-no-se-llenaron-correctamente:]"));
					}
				}
				$this->paginate=array('threaded','order'=>array('Comment.created DESC'),'limit'=>10,'conditions'=>array('status'=>1,'foreign_id'=>$record['Article']['id'],'class'=>'Article'));
				//$this->set("comments",$this->Article->Comment->find("threaded",array('conditions'=>array('status'=>1,'foreign_id'=>$record['Article']['id'],'class'=>'Article'),'order'=>array('Comment.created DESC','limit'=>5))));
				if(empty($this->data)){
					$this->set("comments",$this->paginate("Comment"));
					$this->pageDescription=$record['Article']['description'];
					$this->pageKeywords=$record['Article']['keywords'];
					$page=(isset($this->params['named']['page'])) ? " | ".$this->params['named']['page'] : "";
					$sort=(isset($this->params['named']['direction']) && $this->params['named']['direction']=="desc")? "[:sort_newest:]" : "[:sort_oldest:]";
					$direction=(isset($this->params['named']['direction'])) ? " | ".$sort : "";
					$this->pageTitle=$record['Article']['titulo'].$page.$direction;
				}
				$this->set("rated",in_array($record['Article']['id'],(array)$this->Cookie->read("Rating.Article")));
			}else{
				$this->redirect(array('action'=>'view','id'=>$record['Article']['id'],'slug'=>$record['Article']['slug']),301);
			}
		}else{
			$this->cakeError('error404');
		}
	}

	function get(){
		return $this->Article->find($this->params['type'], $this->params['query']);
	}

	function admin_restore_images($id){
		$this->Article->translate("*");
		$testimonials=$this->Article->find("all",array('contain'=>'Foto','conditions'=>array('Article.id >'=>$id)));
		foreach($testimonials as $testimonial){
			$this->Article->create();
			unset($testimonial['Article']['contenido'],$testimonial['Article']['slug'],$testimonial['Article']['title'],$testimonial['Article']['keywords'],$testimonial['Article']['description'],$testimonial['Article']['titulo']);
			$data=array(
				'Article'=>$testimonial['Article'],
				'Foto'=>array($testimonial['Foto'])
			);
			#pr($testimonial['Article']['id']);
			$this->log($data['Article']['id'],"articles_restore_images");
			if(!$this->Article->save($data,false)){
				$this->log($this->Article->invalidFields(),"articles_restore_images");
			}

		}
		$this->redirect(array('action'=>'index'));
	}

	function rating($id = null,$val = null){
		if(!empty($val) && !empty($id)){
			$this->Article->Rating->set(array('class'=>'Article','foreign_id'=>$id,'value'=>$val));
			if($this->Article->Rating->save()){
				$rating=$this->Cookie->read("Rating.Article");
				$rating[]=$id;
				$this->Cookie->write("Rating.Article",$rating);
				$this->Notifier->success($this->Interpreter->process("[:rating_saved:]"));
			}
		}
		$this->redirect($this->referer());
	}

	function recomended($id=null){
		$this->set("recomended",$this->Article->find("first",array(
				'fields'=>array("Article.id","Article.titulo","Article.slug","Article.comments"),
				'contain'=>array('Foto'),
				'conditions'=>(!$id) ? array('Article.status'=>1) : array('Article.status'=>1,"Article.id <>"=>$id),
				'joins'=>array(array(
					'type'=>'LEFT',
					'table'=>'comments',
					'alias'=>'Comment',
					'conditions'=>array('Comment.foreign_id = Article.id','Comment.class = "Article"','Comment.status = 1')
				)),
				'group'=>array('Article.id'),
				'order'=>'RAND()'
			)
		));
	}

}
?>