<?php
class ContactsController extends AppController{

	var $name = "Contacts";
	var $uses = array('Contact');
	var $helpers = array();
	var $components = array('Captcha','Email');
	var $paginate = array();

	var $ribbonBar = array(
		'title' => '{image:img}[:admin_contacts_title:]',
		'options' => array(
			'image' => array( 'src' => 'icons/48/mail.png', 'alt' => 'Membership' )
		),
		'links' => array()
	);

	function admin_index () {
		$this->set("recordset", $this->paginate("Contact"));
	}


	function admin_delete ( $id=null ) {
		if ( !empty($id) || $this->Xpagin->isExecuter ) {
			$redirect='/admin/contacts/index/';
			if ( empty($id) && !empty($this->data['Xpagin']['record']) ) {
				$id = $this->data['Xpagin']['record'];
			} else if ( empty($id) ) {
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($redirect);
			}
			if ( $this->Contact->deleteAll(array( 'id' => $id )) ) {
				$this->Notifier->success($this->Interpreter->process("[:contact_delete_successfully:]"));
			} else {
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		} else {
			$this->Notifier->error($this->Interpreter->process("[:specify_a_contact_id_add:]"));
		}
		$this->redirect($redirect);
	}

	function add(){
		if(!empty($this->data)){
			$this->data['Contact']['ip']=$_SERVER['REMOTE_ADDR'];
			$this->data['Contact']['referer']=$this->referer();
			$this->Contact->set($this->data);
			if($this->Contact->validates()){
				$this->Captcha->secret = Configure::read("reCAPTCHA.secret");
				if((isset($_POST['g-recaptcha-response']) && $this->Captcha->reCaptcha($_POST['g-recaptcha-response'],env('SERVER_ADDR')))){
					if($this->Contact->save($this->data,false)){
						# Mail interno
						$this->Email->to = Configure::read("Contact");
						$this->Email->bcc = Configure::read('Contact_bcc');
						$this->Email->subject = "Contacto";
						$this->Email->from = $this->data['Contact']['name']." <".$this->data['Contact']['email'].">";
						$this->Email->sendAs = 'html';
						$this->Email->template = 'contactus';
						$this->set('datos',$this->data);
						$this->Email->send();
						#$this->Notifier->success("sended");
						$this->redirect($this->Interpreter->process("/[:thanks_url:]/"));
					}
				}else{
					$this->Contact->invalidate("captcha","[:captcha_error:]");
				}
			}else{

			}
		}
	}

	function captcha($session=null){
		$this->Captcha->image($session);
	}

}
?>