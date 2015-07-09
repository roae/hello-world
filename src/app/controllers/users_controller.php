<?php
/**
 * Class UsersController
 * @property $User USer
 */
class UsersController extends AppController{
	var $name = 'Users';
	var $uses=array(
		'User'
	);

	var $components = array(
		'Captcha',
		'Email',
	);

	var $ribbonBar=array(
		'title'=>'{image:img} [:admin_security_title:]',
		'options'=>array(
			'image'=>array('src'=>'admin/ribbon_security.png','alt'=>'Securirty')
		),
		'links'=>array()
	);

	var $paginate=array('fields'=>array('id','username','created','modified','status','Group.name'),'limit'=>10);

	function beforeFilter(){
		$this->Auth->allow('logout');
		parent::beforeFilter();
	}

	function admin_login(){
		$this->layout="login";
		$this->login();
	}

	function admin_logout() {
		$this->logout();
	}

	function admin_index(){
		$this->set("recordset",$this->paginate("User"));
	}

	function admin_add(){
		if(!empty($this->data)){
			#pr($this->data['User']['password']);
			#$this->data['User']['password_confirm'] = $this->Auth->password($this->data['User']['password_confirm']);
			$this->User->set($this->data['User']);
			if($this->User->validates()){
				if($this->_permit($this->data['User']['group_id'])){
					$this->data['User']['status'] = 1;
					$this->User->save($this->data,false);
					$this->Acl->allow($this->User,$this->User);
					$this->Notifier->success('[:admin_user_save_success:]');
					$this->redirect(array('action' => 'index'));
				}else{
					$this->User->invalidate('grupo_id','[:admin_no_tiene_permisos_de_agregar_a_este_grupo:]');
				}
			}
			#pr($this->User->invalidFields());
			unset($this->data['User']['password'],$this->data['User']['password_confirm']);
			$this->Session->setFlash('[:admin_user_add_error:]','default',array('class' => 'error'));
		}
		$this->set('groups',$this->User->Group->find('list',array('fields'=>array('id','name'),'order' => array('Group.name' => 'ASC'))));
	}

	function admin_edit($id){
		if(!empty($this->data)){
			#$this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
			$this->User->set($this->data['User']);
			unset($this->User->validate['password'], $this->User->validate['password_confirm']);
			if($this->User->validates()){
				#if($this->_permit($this->data['User']['group_id'])){
					//$this->data['User']['status'] = 1;
					$this->User->save($this->data,false);
					#$this->Acl->allow($this->User,$this->User);
					$this->Notifier->success('[:admin_user_update_success:]');
					$this->redirect(array('action' => 'index'));
				#}else{
					//$this->User->invalidate('grupo_id','[:admin_no_tiene_permisos_de_agregar_a_este_grupo:]');
				#}
			}
			#pr($this->User->invalidFields());
			//unset($this->data['User']['password'],$this->data['User']['password_confirm']);
			$this->Notifier->error('[:admin_user_edit_error:]');
		}
		$this->set('groups',$this->User->Group->find('list',array('fields'=>array('id','name'),'order' => array('Group.name' => 'ASC'))));
		$this->User->id=$id;
		$this->data=$this->User->read();
	}

	function admin_password($id = null){
		if(!empty($this->data)){
			unset(
				$this->User->validate['nombre'],
				$this->User->validate['paterno'],
				$this->User->validate['materno'],
				$this->User->validate['group_id'],
				$this->User->validate['username']
			);
			$this->data['User']['password']=$this->Auth->password($this->data['User']['password']);
			$this->data['User']['id'] = $id;
			$this->User->set($this->data);
			if($this->User->validates(array('fieldList'=>array('password','password_confirm'))) && $this->User->save($this->data,false)){
				$this->Notifier->success("[:admin_user_password_change_success:]");
				$this->redirect(array('action'=>'index'));
			}else{
				$this->Notifier->error("[:validation_errors:]");
			}
		}
	}

	function admin_status($state=null, $id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect("/admin/users/index/");
			}
			if(!empty($state) || $state == 0){
				if($this->User->updateAll(array('User.status' => $state), array('User.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:User_publish_successfully:]" : "[:User_unpublish_successfully:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_User_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect("/admin/users/index");
		}
	}

	function admin_dashboard() {

	}

	function login(){
		if(!empty($this->data)){
			if($this->Auth->login()){
				if($this->Auth->user("group_id") != Configure::read("Group.Registered")){
					#pr("/".$this->Session->read("Auth.redirect"));
					$this->redirect("/admin/");
				}
				if($this->Session->check("SigninReferer")){
					$this->redirect($this->Session->read("SigninReferer"));
				}
				$this->redirect($this->referer());
			}else{
				$this->Notifier->error('[:username_or_password_incorrect:]');
				$this->User->invalidate('username'," ");
				$this->User->invalidate('password'," ");
				unset($this->data['User']['password']);
			}
		}
	}

	function logout(){
		if($this->Auth->logout()){
			$this->Session->delete("LoggedProfile");
		}
		$this->redirect("/");
	}

	function _permit($group_id,$action = "update"){
		return !Configure::read('Acl.active') || $this->Acl->check($this->Auth->user(),array('model' => 'Group','foreign_key' => $group_id),$action);
	}

	function profile(){
		$this->User->id = $this->Auth->user("id");
		$this->User->contain(array(
			"Profile",
			'SocialAuth',
			'Buy'=>array(
				'Movie'=>array(
					'Poster'
				),
				'Location',
				'Projection'
			)
		));
		$this->set("record",$this->User->read());
	}

	function edit_profile(){
		if(!empty($this->data)){
			# Se quitan las validaciones que no se necesitan en este caso
			unset($this->User->validate['username'],$this->User->validate['password'],$this->User->validate['password_confirm'],$this->User->validate['group_id']);
			$this->data['User']['id'] = $this->loggedUser['User']['id'];
			$this->data['Profile']['id'] = $this->loggedProfile['id'];
			$this->User->set($this->data);
			if($this->User->saveAll($this->data)){
				//pr(am($this->data['Profile'],$this->loggedProfile));
				$this->Session->write("LoggedProfile",am($this->data['Profile'],$this->loggedProfile));

				$this->Session->write("Auth",am($this->loggedUser['User'],$this->data['User']));
				$this->Auth->login($this->data['User']);
				$this->Notifier->success("[:profile-changes-saved:]");
				$this->redirect(array('controller'=>'users','action'=>'profile'));
			}

		}
		$this->data = $this->User->find("first",array(
			'conditions'=>array(
				'User.id'=>$this->loggedUser['User']['id'],
			),
			'contain'=>array(
				'Profile'
			)
		));
	}

	function set_password(){
		$this->User->id = $this->loggedUser['User']['id'];
		$record = $this->User->read();
		if(!empty($this->data)){
			$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
			$this->data['User'] = am($record['User'],$this->data['User']);
			$this->User->set($this->data['User']);

			if($this->User->save()){
				$this->Notifier->success("[:password-seted-successfully:]");
				$this->redirect(array('action'=>'profile'));
			}

		}
		$this->set("record",$record);
	}

	function change_password(){
		$this->User->id = $this->loggedUser['User']['id'];
		$record = $this->User->read();
		if(!empty($this->data)){
			$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
			$this->data['User']['current_password'] = $this->Auth->password($this->data['User']['current_password']);
			if($record['User']['password'] == $this->data['User']['current_password']){
				$this->data['User'] = am($record['User'],$this->data['User']);
				$this->User->set($this->data['User']);

				if($this->User->save()){
					$this->Notifier->success("[:password-changed-successfully:]");
					$this->redirect(array('action'=>'profile'));
				}
			}else{
				$this->User->invalidate("current_password","[:password-incorrect:]");
			}


		}
		$this->set("record",$record);
	}

	function singin($provider = null){
		$route = Router::parse($this->referer());
		##pr($route);
		if(!empty($route) && ($route['controller']!='users' || $route['action']!='singin')){
			$this->Session->write("SigninReferer",$this->referer());
		}

		if(!empty($provider)){
			$this->__socialConnect($provider);
			//$this->__register(false);
		}else{
			if(!empty($this->data)){
				$this->__register(false,$provider);
			}
		}
	}

	function __register($reCaptcha = true,$provider = null){
		$this->data['User']['group_id']=Configure::read("Group.Registered");
		$this->data['User']['code']=String::uuid();
		$this->data['User']['email'] = $this->data['User']['username'];
		$this->User->set($this->data);

		if($this->User->validates()){

			$this->Captcha->secret = Configure::read("reCAPTCHA.secret");
			if((isset($_POST['g-recaptcha-response']) && $this->Captcha->reCaptcha($_POST['g-recaptcha-response'],env('SERVER_ADDR'))) || !$reCaptcha){
				$error = false;
				if((isset($this->data['User']['terms']) && $this->data['User']['terms'])  || $provider){
					$this->User->begin();
					if($this->User->save($this->data['User'],false)){
						//if(isset($this->data['Profile'])){
							$this->data['Profile']['user_id'] = $this->User->id;
							$this->User->Profile->set($this->data['Profile']);
							if(!$this->User->Profile->save()){
								$error = true;
							}
						//}
						if(isset($this->data['SocialAuth'])){
							$this->data['SocialAuth']['user_id'] = $this->User->id;
							$this->User->SocialAuth->set($this->data['SocialAuth']);
							if(!$this->User->SocialAuth->save()){
								$error = true;
							}
						}
						if($error){
							$this->User->rollback();
						}else{
							$this->User->commit();
						}

						#$this->Email->layout="invitacion";
						$this->Email->to = $this->data['User']['username'];
						$this->Email->subject = 'Active su usuario de Citicinemas';
						$this->Email->from = "Citicinemas Mobil <noreply@citicinemas.com>";
						$this->Email->name="Citicinemas Mobil";
						$this->Email->sendAs = 'html';
						$this->Email->template = 'confirm_user';
						$this->set('email',$this->data['User']['username']);
						$this->set("code",$this->data['User']['code']);
						/* Opciones SMTP*
						$this->Email->smtpOptions = array(
							'port'=>'25',
							'timeout'=>'30',
							'host' => 'mail.h1webstudio.com',
							'username'=>'erochin@h1webstudio.com',
							'password'=>'Rochin12!-');

						$this->Email->delivery = 'smtp';
						/**/
						$this->Email->send();
						//pr($this->Email->smtpError);
						$this->Auth->login($this->data['User']);
						$this->redirect(array('action'=>'profile'));
					}else{
						$error = true;
					}
					if($error){
						$this->User->rollback();
					}else{
						$this->User->commit();
					}
				}else{
					$this->User->invalidate("terms","[:debe-aceptar-terminos:]");
				}
			}
		}else{
			//pr($this->User->validationErrors);
		}
		$this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
	}

	function __socialConnect($provider){
		require_once( WWW_ROOT . "hybridauth/Hybrid/Auth.php" );

		try{
			// create an instance for Hybridauth with the configuration file path as parameter
			Configure::write("HybridAuth.base_url",(env('HTTPS') ? 'https://' : "http://").$_SERVER['HTTP_HOST'].$this->base . "/hybridauth/");

			$hybridauth = new Hybrid_Auth(Configure::read("HybridAuth"));
			// try to authenticate the selected $provider
			$adapter = $hybridauth->authenticate( $provider );
			// grab the user profile
			$user_profile = $adapter->getUserProfile();
			//pr($user_profile);

			$_provider = $this->User->SocialAuth->find("first",array(
				'conditions'=>array(
					'SocialAuth.provider_uid'=>$user_profile->identifier
				),
				'contain'=>array(
					'User'=>array(
						'Profile'
					)
				)
			));

			if(!empty($_provider)){
				#pr($_provider);
				$this->__login($_provider);
				#$route = Router::url($this->Session->read("Signin"))
				$this->redirect($this->Session->read("SigninReferer"));
			}else{
				$_user = $this->User->findByUsername($user_profile->email);
			}
			if(!empty($_user)){
				$this->redirect(array('action'=>'login'));
			}else{
				$this->__setData($user_profile);
				$this->__register(false);
			}
			//pr($user_profile);
			$this->set( 'user_profile',  $user_profile );

			$this->__setData($user_profile);
		}
		catch( Exception $e ){
			// Display the recived error
			switch( $e->getCode() ){
				case 0 : $error = "Unspecified error."; break;
				case 1 : $error = "Hybriauth configuration error."; break;
				case 2 : $error = "Provider not properly configured."; break;
				case 3 : $error = "Unknown or disabled provider."; break;
				case 4 : $error = "Missing provider application credentials."; break;
				case 5 : $error = "Authentification failed. The user has canceled the authentication or the provider refused the connection."; break;
				case 6 : $error = "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.";
					$adapter->logout();
					break;
				case 7 : $error = "User not connected to the provider.";
					$adapter->logout();
					break;
			}
			// well, basically your should not display this to the end user, just give him a hint and move on..
			$error .= "<br /><br /><b>Original error message:</b> " . $e->getMessage();
			$error .= "<hr /><pre>Trace:<br />" . $e->getTraceAsString() . "</pre>";
			$this->set( 'error',  $error );
			//pr($error);
		}
	}

	function __providerRegistered($data){
		if(isset($data['identifier'])){
			$this->User->SocialAuth->findByProvierUid($data['identifier']);
		}
	}

	function __setData($data){
		$this->data['User']['username'] = $data->email;
		# Se pone un password al usuario solo para que pase la validación
		$this->data['User']['password'] = $this->Auth->password(Configure::read("Security.salt"));
		$this->data['User']['password_confirm'] = Configure::read("Security.salt");
		$this->data['User']['group_id'] = Configure::read("Group.Registered");
		$this->data['User']['nombre'] = $data->firstName;
		$this->data['User']['paterno'] = $data->lastName;
		$this->data['User']['trash'] = 0;
		$this->data['User']['status'] = 1;
		$this->data['User']['confirmed'] = date("Y-m-d h:i:s");
		$this->data['User']['signed_in'] = date("Y-m-d h:i:s");
		$this->data['User']['signed_count'] = 1;

		$this->data['Profile']['gender'] = $data->gender;
		$this->data['Profile']['age'] = $data->age;
		$this->data['Profile']['photo_url'] = $data->photoURL;
		$this->data['Profile']['cel'] = $data->phone;
		$this->data['Profile']['birthday'] = $data->birthYear."-".$data->birthMonth."-".$data->birthDay;

		$this->data['SocialAuth']['provider'] = $this->params['pass'][0];
		$this->data['SocialAuth']['provider_uid'] = $data->identifier;
		$this->data['SocialAuth']['email'] = $data->email;
		$this->data['SocialAuth']['display_name'] = $data->displayName;
		$this->data['SocialAuth']['first_name'] = $data->firstName;
		$this->data['SocialAuth']['last_name'] = $data->lastName;
		$this->data['SocialAuth']['website_url'] = $data->webSiteURL;
		$this->data['SocialAuth']['profile_url'] = $data->profileURL;

		#$this->User->set($this->data);
	}

	function __login($data){
		if($this->Auth->login($data)){
			$this->Session->write("LoggedProfile",$data['Profile']);
		}
	}

	function confirm($uid=null){
		if(!empty($uid)){
			$subscription=$this->User->findByCode($uid);
			if(!empty($subscription)){
				#$data=array('status'=>1,'id'=>$subscription['User']['id']);

				$subscription['User']['status'] = 1;
				$subscription['User']['confirmed'] = date("Y-m-d H:i:s");
				unset($this->User->validate['password_confirm']);
				$this->set("data",$subscription);
				$this->User->set($subscription);
				if($this->User->save()){
					$this->Notifier->success("[:user_confirm_successfully:]");
					$this->set("done",'successfully');
					$this->Auth->login($subscription);
					$this->redirect(array('controller'=>'users','action'=>'profile'));
				}else{
					pr($this->User->validationErrors);
					$this->set("done",'error');
				}
			}else{
				$this->set("done",'no_existe');
			}
		}else{
			$this->set("done",'no_existe');
		}

	}

	function send_confirmation(){
		$this->Email->to = $this->loggedUser['User']['username'];
		$this->Email->subject = 'Active su usuario de Citicinemas';
		$this->Email->from = "Citicinemas Mobil <noreply@citicinemas.com>";
		$this->Email->name="Citicinemas Mobil";
		$this->Email->sendAs = 'html';
		$this->Email->template = 'confirm_user';
		$this->set('email',$this->loggedUser['User']['username']);
		$this->set("code",$this->loggedUser['User']['code']);
		/* Opciones SMTP*
		$this->Email->smtpOptions = array(
			'port'=>'25',
			'timeout'=>'30',
			'host' => 'mail.h1webstudio.com',
			'username'=>'erochin@h1webstudio.com',
			'password'=>'Rochin12!-');

		$this->Email->delivery = 'smtp';
		/**/
		//pr($this->Email->smtpError);
		if($this->Email->send()){
			$this->Notifier->success("[:email_user_confirmation_sended:]");
		}else{
			$this->Notifier->success("[:email_user_confirmation_error:]");
		}

		$this->redirect($this->referer());


	}
}
?>