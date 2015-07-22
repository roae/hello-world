<?php
class ReverseTransactionsShell extends Shell{
	/*
	 * SoapClient
	 */
	var $VistaServer;

	/**
	 * Models
	 * @var array
	 */
	var $uses = array(
		'Setting',
	);

	var $config;
	var $Email = false;


	function startUp(){
		$this->Dispatch->clear();
	}

	/**
	 * Metodo que se ejecuta automaticamente desde el comando de consola
	 */
	function main(){
		//Configure::write("debug",2);
		try{
			//$this->log(print_r(range(0,59)));
			$this->config = $this->Setting->getConfig();
			Configure::write("AppConfig",$this->config);
			App::import('Core', 'Controller');
			App::import('Controller', 'App');
			$Controller = & new Controller();
			App::import('Component', 'SmartConnector');
			$smartConnector =& new SmartConnectorComponent();
			$smartConnector->initialize($Controller);
			$smartConnector->settings = array(
				'hosts'=>$this->config['smart_url'],
				'clientID'=>$this->config['smart_clientID'],
				'clientPOS'=>$this->config['smart_clientPOS'],
				'user'=>$this->config['smart_user'],
				'passwd'=>$this->config['smart_passwd'],
				'randomKey'=>'6BD2A20879A987AC46A24121356478B8',
			);

			$seccond =0;
			while($seccond < 60){
				$start = time();

				$transactions = $this->__getCache();
				//$this->log($transactions,"SmartConnector");
				#$this->out("Start: ".date("H:i:s",$start));

				if(!empty($transactions)){
					foreach($transactions as $i => $transaction){
						if(!$transaction['working']){
							if(($start - $transaction['time']) <= 2*60){
								if($transaction['attempts'] == 2 && ($start - $transaction['last_attempt']) >= 3 && ($start - $transaction['last_attempt']) < 10){
									//$this->out("intento: ".($transaction['attempts']+1)." - ".date("H:i:s",$start));
									$this->log("Intento reverso (".($transaction['attempts']+1).")","SmartConnector");
									#$this->hr();
									$transactions[$i]['attempts'] ++;
									$transactions[$i]['last_attempt'] = time();
									$transaction[$i]['working'] = true;
									$this->__setCache($transactions);
									$smartResponse = $smartConnector->reverse($transaction['data'],$transaction['motivo'],true);
									if(isset($smartResponse['Aut-Code'])){
										unset($transactions[$i]);
									}else{
										$transaction[$i]['working'] = false;
										$this->__setCache($transactions);
									}
								}else if($transaction['attempts'] > 2 && ($start - $transaction['last_attempt']) >= 10){
									//$this->out("- intento: ".($transaction['attempts']+1)." - ".date("H:i:s",$start));
									$this->log("Intento reverso (".($transaction['attempts']+1).")","SmartConnector");
									//$this->hr();
									$transactions[$i]['attempts'] ++;
									$transactions[$i]['last_attempt'] = time();
									$transaction[$i]['working'] = true;
									$this->__setCache($transactions);
									$smartResponse = $smartConnector->reverse($transaction['data'],$transaction['motivo'],true);
									if(isset($smartResponse['Aut-Code'])){
										unset($transactions[$i]);
									}else{
										$transaction[$i]['working'] = false;
										$this->__setCache($transactions);
									}
								}
							}else{
								#Enviar mail
								$this->__sendErrorEmail($transactions[$i]['attempts'],$transactions[$i]['time']);
								$this->log("Se envio un email a ubaldo@citicinemas.com");
								//$this->out("Envio de mail");

								unset($transactions[$i]);
								$this->__setCache($transactions);
							}
						}
					}

					//$this->hr(1);

				}
				$end = time();
				/*$this->out("start: ".$start);
				$this->out("end: ".$end-$start);*/
				$time = $end-$start;
				$seccond += $time? $time : 1;
				//$this->out($seccond);

				sleep(1);
			}
		}catch (Exception $e){
			$this->out($e->getMessage());
		}


		$this->_stop(1);

	}

	function __sendErrorEmail($attempts,$time){
		if(!$this->Email){
			App::import('Core', 'Controller');
			App::import('Controller', 'App');
			$Controller = & new Controller();
			App::import('Component', 'Email');
			$this->Email = new EmailComponent();
			$this->Email->initialize($Controller);
		}

		$this->Email->reset();
		$this->Email->to = "ubaldo@citicinemas.com,erochin@grupoadhoc.mx";#$this->config['sync_error_email'];
		$this->Email->from = "erochin@h1webstudio.com";
		$this->Email->subject = "Error en Reverso";
		$this->Email->sendAs = 'html';
		$Controller->set("attempts",$attempts);
		$Controller->set("time",$time);
		$this->Email->layout = "notifications";
		$this->Email->template = "reverse_error";


		/* Opciones SMTP*/
		$this->Email->smtpOptions = array(
			'port'=>'25',
			'timeout'=>'30',
			'host' => 'mail.h1webstudio.com',
			'username'=>'erochin@h1webstudio.com',
			'password'=>'Rochin12!-');

		$this->Email->delivery = 'smtp';
		/**/
		$this->Email->send();

		$this->out(print_r($this->Email->smtpError));
	}

	function __getCache(){
		Cache::set(array('duration' => '+1 day'));
		return Cache::read("reverse_transactions");
	}

	function __setCache($data){
		Cache::set(array('duration' => '+1 day'));
		Cache::write("reverse_transactions",$data);
	}

}
?>