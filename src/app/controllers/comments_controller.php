<?php
class CommentsController extends AppController{

	var $name = "Comments";
	var $uses = array("Comment","Article");
	var $ribbonBar = array(
		'title' => '{image:img} [:admin_Comments_title:]',
		'options' => array(
			'image' => array('src' => 'icons/48/help.png', 'alt' => 'Comments')
		),
		'links' => array()
	);
	var $paginate = array('limit' => 50);

	#var $helpers = array('Media.Uploader' => 'Form');

	var $components =array('Captcha');

	function admin_index(){
		$this->params['named']['class']=isset($this->params['named']['class']) ? $this->params['named']['class'] : "Article";
		$conditions=array(
			'Comment.class'=>$this->params['named']['class'],
		);
		if(!isset($this->params['named']['status'])){
			$conditions=am($conditions,array('Comment.status <'=>2));
		}else{
			$conditions=am($conditions,array('Comment.status'=>$this->params['named']['status']));
		}
		if(isset($this->params['named']['foreign_id'])){
			$this->data['Xpagin']['Article']=$this->params['named']['foreign_id'];
			$conditions=am($conditions,array('Comment.foreign_id'=>$this->params['named']['foreign_id']));
		}
		$this->paginate=array(
			'limit'=>50,
			'conditions'=>$conditions,
			'order'=>'Comment.created desc'
		);

		$this->set("articles",$this->Article->find("list"));

		$this->set("recordset", $this->paginate("Comment"));
	}

	function admin_add(){
		if(!empty($this->data)){
			$this->Comment->set($this->data);
			if($this->Comment->validates()){
				if($this->Comment->save()){
					$this->Notifier->success("[:Comment_saved_successfully:]");
					$this->redirect("/admin/comments/index/");
				}else{
					$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
				}
			}else{
				pr($this->Comment->invalidFields());
				$this->Notifier->error("[:some_fields_invalid:]");
			}
		}
		$this->set("tags",$this->Comment->Tag->find("list"));
	}

	function admin_edit($id=null){
		if(!empty($id)){
			if(!empty($this->data)){
				$this->Comment->set($this->data);
				if($this->Comment->validates()){
					if($this->Comment->save($this->data, false)){
						$this->Notifier->success("[:Comment_saved_successfully:]");
						$this->redirect("/admin/comments/index");
					}else{
						$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
					}
				}else{
					$this->Notifier->error("[:some_fields_invalid:]");
				}
			}else{
				$this->data = $this->Comment->read(null, $id);
			}
		}else{
			if($this->Xpagin->isExecuter){
				if(empty($this->data['Xpagin']['record'])){
					$this->Notifier->error("[:select_a_comment_to_edit:]");
				}else{
					$this->redirect("/admin/comments/edit/" . $this->data['Xpagin']['record'][0]);
				}
			}else{
				$this->Notifier->error("[:specify_a_comment_id_edit:]");
			}
		}
		//$this->set("tags",$this->Comment->Tag->find("list"));
	}

	function admin_delete($id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			$redirect = '/admin/comments/index/';
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($redirect);
			}
			if($this->Comment->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Comment_delete_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Comment_id_add:]"));
		}
		$this->redirect($redirect);
	}

	function admin_status($state=null, $id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect("/admin/comments/index/");
			}
			if(!empty($state) || $state == 0){
				if($this->Comment->updateAll(array('Comment.status' => $state), array('Comment.id' => $id))){
					$this->Notifier->success($this->Interpreter->process("[:Comment_statu_$state:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Comment_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect("/admin/comments/index");
		}
	}

	function add(){
		if(!empty($this->data)){
			$this->Comment->set($this->data);
			if($this->Comment->validates()){
				if($this->Comment->save($this->data,false)){
					$this->Notifier->success("[:comment_posted:]");
				}else{
					$this->Notifier->success("[:ocurreio_error_en_el_servidor:]");
				}
			}else{
				#$this->Notifier->error("[:algunos_campos-no-se-llenaron-correctamente:]");
			}
		}
		//$this->redirect($this->referer());
	}



	function get(){
		return $this->Comment->find($this->params['type'], $this->params['query']);
	}

	function admin_restore(){
		$recordset=$this->Comentario->find("threaded",array('conditions'=>array('Comentario.proyecto_id'=>$this->params['named']['id_ant'],"Comentario.state"=>1)));
		$this->__recursive_restore($recordset);
		$this->set("recordset",$recordset);
		//$this->redirect("/admin/");
	}

	function __recursive_restore($recordset,$parent=null){
		foreach((array)$recordset as $record){
			$data['Comment']=array(
				'hotel_id'=>$record['Comentario']['proyecto_id'],
				'class'=>'Hotel',
				'foreign_id'=>$this->params['named']['id_act'],
				'nombre'=>$record['Comentario']['fullname'],
				'message'=>$record['Comentario']['message'],
				'email'=>$record['Comentario']['email'],
				#'tel'=>$record['Comentario']['phone'],
				'status'=>1,
				'parent_id'=>$parent,
				'created'=>$record['Comentario']['created']
			);
			#pr($data);
			$this->Comment->create();
			$this->Comment->set($data);
			$this->Comment->save();
			#pr($this->Comment->invalidFields());

			$this->__recursive_restore($record['children'],$this->Comment->id);

		}
	}

}

?>
