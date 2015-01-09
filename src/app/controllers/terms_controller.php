<?php
/**
 * TermsController
 *
 * @property Term $Term
 */

App::import('Sanitize');
class TermsController extends AppController{

	var $name = "Terms";
	var $uses = array("Term");
	var $ribbonBar = array(
		'title' => '{image:img} [:admin_Terms_title:]',
		'options' => array(
			'image' => array('src' => 'icons/48/help.png', 'alt' => 'Terms')
		),
		'links' => array()
	);
	var $paginate = array(
		'threaded',
		'fields'=>array('Term.id','Term.nombre','Term.descripcion','Term.slug','Term.cantidad','Term.parent_id'),
		'joins'=>array(
			array(
				'type'=>'left',
				'table'=>'articles_terms',
				'alias'=>'Article',
				'conditions'=>array('Term.id = Article.term_id')
			),
		),
		'order'=>array('Term.nombre'=>'ASC'),
		'limit' => 20,
		'group'=>'Term.id',
	);

	function admin_index($class){
		$conditions= array(
			'Term.class'=>$class
		);
		if(isset($this->data['Xpagin']['search']) && !empty($this->data['Xpagin']['search'])){
			$conditions = array(
				'Term.nombre LIKE'=>'%'.Sanitize::escape($this->data['Xpagin']['search']).'%',
			);
		}
		$this->set("recordset", $this->paginate("Term",$conditions));
		if($class == "Category"){
			$this->set("parents",$this->Term->generatetreelist(array('Term.class'=>'Category'), null, null, '-- '));
		}

	}

	function admin_add($class){
		if(!empty($this->data)){
			$this->data['Term']['class'] = $class;
			$this->Term->set($this->data);
			if($this->Term->validates()){
				if($this->Term->save($this->data, false)){
					$this->Notifier->success("[:Term_saved_successfully:]");
					$this->redirect(array('action'=>'index','class'=>$this->params['class']));
				}else{
					$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
				}
			}else{
				#$this->Notifier->error("[:some_fields_invalid:]");
			}
		}
		if($class == "Category"){
			$this->set("parents",$this->Term->generatetreelist(array('Term.class'=>'Category'), null, null, '-- '));
		}
	}

	function admin_edit($class,$id){
		if(!empty($id) || (isset($this->data['Term']['id']) && !empty($this->data['Term']['id'])) ){
			if(!empty($this->data)){
				$this->Term->set($this->data);
				if($this->Term->validates()){
					if($this->Term->save($this->data, false)){
						$this->Notifier->success("[:Term_saved_successfully:]");
						$this->redirect(array('action'=>'index','class'=>$class));
					}else{
						$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
					}
				}else{
					$this->Notifier->error("[:some_fields_invalid:]");
				}
			}else{
				//pr($this->params);
				$this->data = $this->Term->read(null, $id);
				if($this->params['class'] == "Category"){
					$this->set("parents",$this->Term->generatetreelist(array('Term.class'=>'Category'), null, null, '-- '));
				}
				//pr($this->data);
			}
		}else{
			if($this->Xpagin->isExecuter){
				if(empty($this->data['Xpagin']['record'])){
					$this->Notifier->error("[:select_a_term_to_edit:]");
				}else{
					$this->redirect("/admin/terms/edit/" . $this->data['Xpagin']['record'][0]);
				}
			}else{
				$this->Notifier->error("[:specify_a_term_id_edit:]");
			}
		}
	}

	function admin_delete($class,$id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			$redirect = '/admin/terms/index/';
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($redirect);
			}
			if($this->Term->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:{$class}_delete_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_{$class}_id_add:]"));
		}
		$this->redirect($redirect);
	}

	function get(){
		return $this->Term->find($this->params['type'], $this->params['query']);
	}

	function autocomplete($term){
		//pr($term);
		$conditions=array(
			'Term.nombre LIKE'=>"%$term%",
			'Term.class'=>'Tag',
		);
		if(!empty($this->data['Article']['Term'])){
			$conditions=am($conditions,array('NOT'=>array('Term.id'=>$this->data['Article']['Term'])));
		}
		$terms=$this->Term->find('list',array(
				'conditions'=>$conditions,
				'order'=>array(
					"Term.nombre LIKE '$term%'"=>"DESC",
					"Term.nombre"=>"ASC"
				)
			));

		$response=array();
		foreach($terms as $id=>$term){
			$response[]=array('id'=>$id,'label'=>$term,'value'=>$term);
		}
		$this->set("response",$response);
		header("Content-Type: application/json; charset=UTF-8");

	}

}

?>
