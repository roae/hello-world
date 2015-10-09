<?php
class ContactsController extends AppController{

	var $name = "Contacts";
	var $uses = array(
		'Contact',
		"Location"
	);
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
			$this->Captcha->secret = Configure::read("reCAPTCHA.secret");
			$captcha = (isset($_POST['g-recaptcha-response']) && $this->Captcha->reCaptcha($_POST['g-recaptcha-response'],env('SERVER_ADDR')));
			if($this->Contact->validates() * $captcha){
				if($this->Contact->save($this->data,false)){
					# Mail interno
					//$this->Email->layout="notifications";
					$to = Configure::read("AppConfig.contact_email");
					$bcc = explode(",",Configure::read('AppConfig.contact_email_cc'));
					if(!empty($this->data['Contact']['manager']) && $this->data['Contact']['manager'] != "-1"){
						$to = $this->data['Contact']['manager'];
						$bcc[] = Configure::read("AppConfig.contact_email");
					}
					$this->Email->to = $to;
					$this->Email->bcc = $bcc;
					$this->Email->subject = "Contacto";
					$this->Email->from = $this->data['Contact']['name']." <".$this->data['Contact']['email'].">";
					#$this->Email->from = "erochin@h1webstudio.com";
					$this->Email->sendAs = 'html';
					$this->Email->template = 'contactus';
					$this->set('datos',$this->data);
					/* Opciones SMTP *
					$this->Email->smtpOptions = array(
						'port'=>'25',
						'timeout'=>'30',
						'host' => 'mail.h1webstudio.com',
						'username'=>'erochin@h1webstudio.com',
						'password'=>'Rochin12!-');

					$this->Email->delivery = 'smtp';
					/**/
					if($this->Email->send()){
						$this->redirect($this->Interpreter->process("/[:thanks_url:]/"));
					}else{
						#pr($this->Email->smtpError);
					}
					#$this->Notifier->success("sended");

				}
			}else{
				if(!$captcha){
					$this->Contact->invalidate("captcha","[:captcha_error:]");
				}

			}
		}
		$this->set("managers",$this->Location->find("list",array(
			'fields'=>array('Location.manager_email','Location.name'),
			'conditions'=>array('Location.manager_email IS NOT NULL','Location.status'=>1,'Location.trash'=>0),
		)));
	}

	function captcha($session=null){
		$this->Captcha->image($session);
	}

}
?>