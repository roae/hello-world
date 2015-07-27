<?php
App::Import('Core','Xml');
/**
 * Class SmartConnectorComponent
 *
 * Hace la coneccion con el servicio de SmartDb (empresa de pagos)
 *
 */
class SmartConnectorComponent extends object{
	/**
	 *	Nombre del component.
	 *
	 *	@var string
	 *	@access public
	 */
	var $name = 'SmartConnector';

	var $stan  = 1;

	//var $settings = array();

	var $settings;

	var $controller;

	var $headers = array(
		'Accept: application/xml',
		'Content-Type: application/xml; charset=UTF-8'
	);


	function initialize( &$controller, $settings=array() ) {
		#$this->controller = $controller;
		$this->settings = am($this->settings, $settings);
		$this->Setting = ClassRegistry::init('Setting');
	}


	function login( ){
		if(!$this->__isLogged()){
			$stan = $this->__getStan();
			setTimezoneByOffset(-7);
			$time = date('dmYHis', time());

			$authData = "12={$this->settings['user']}
	    13={$this->settings['passwd']}
	    14={$this->settings['randomKey']}
	    19={$stan}";

			openssl_public_encrypt($authData, $authDataCrypt, $this->__getPubKey());
			$authXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	    <sbt-ws-message version=\"1.0\">
	        <header>
	            <Type>000100</Type>
	            <ClientID>{$this->settings['clientID']}</ClientID>
	            <SerialPos>{$this->settings['clientPOS']}</SerialPos>
	            <Stan>$stan</Stan>
	            <DeviceTime>$time</DeviceTime>
	        </header>
	        <message>
	            <DataCipher><![CDATA[" . base64_encode($authDataCrypt) . "]]></DataCipher>
	        </message>
	    </sbt-ws-message>";

			$this->log("[Login] Request: ".json_encode(array(
				'Type'=>'000100',
				'ClientID'=>$this->settings['clientID'],
				'SerialPos'=>$this->settings['clientPOS'],
				'Stan'=>$stan,
				'DeviceTime'=>$time,
			)),"SmartConnector");

			try{
				$process = curl_init($this->settings['hosts']);
				curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
				curl_setopt($process, CURLOPT_HEADER, 0);
				curl_setopt($process, CURLOPT_TIMEOUT, 30);
				curl_setopt($process, CURLOPT_POST, 1);
				curl_setopt($process, CURLOPT_POSTFIELDS, $authXML);
				curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
				$return = curl_exec($process);
				//$xml=simplexml_load_string($return);
				$xml = new Xml($return);
				$xmlData = $xml->toArray();
				curl_close($process);

				#pr($xmlData);

				if(isset($xmlData['Sbt-ws-message']['Header']['Resp-Code'])){
					switch($xmlData['Sbt-ws-message']['Header']['Resp-Code']){
						case '00':
							$this->Setting->saveAll(array(
								array(
									'id'=>19,
									'value'=>mktime(0,0,0,date("m"),date("d"),date("Y")),
								),
								array(
									'id'=>20,
									'value'=>$xmlData['Sbt-ws-message']['Header']['LastServerKey'],
								),
								array(
									'id'=>17,
									'value'=>1,
								),
								array(
									'id'=>18,
									'value'=>2,
								)
							));
							$this->log("[Login] Response: ".json_encode($xmlData['Sbt-ws-message']['Header'])." | ".json_encode($xmlData['Sbt-ws-message']['Message']),"SmartConnector");
							#Cache::set(array('duration' => '+30 days'));
							#Cache::write("smart_connector",$cache);
							setTimezoneByOffset(-7);
							return true;
							break;
						default:
							$messageJSON = isset($xmlData['Sbt-ws-message']['Message']) ? json_encode($xmlData['Sbt-ws-message']['Message']) : "";
							$this->log("[Login] Response Error: ".json_encode($xmlData['Sbt-ws-message']['Header'])." | ".$messageJSON,"SmartConnector");
							break;
					}
					setTimezoneByOffset(-7);
					return false;
				}else{
					setTimezoneByOffset(-7);
					$this->log("[Login] Response Error: No hubo respuesta del servidor de smart","SmartConnector");
					return false;

				}

			}catch (Exception $e){
				$this->log("[Login] Response Error: ".$e->getMessage(),"SmartConnector");
				return false;
			}
		}else{
			return true;
		}

	}

	function payment($data){
		if($this->login()){
			$stan = $this->__getStan();
			//$this->out($stan);
			#pr("stan $stan");
			setTimezoneByOffset(-6);
			$time = date('dmYHis', time());
			//date_default_timezone_set("UTC");
			$dataText = $this->__buildDataText(array(
				'05'=>$data['total'],
				'06'=>'1',
				'07'=>'0',
				'08'=>'0',
				'09'=>'0',
				'16'=>'01',
				'17'=>'08',
				'64'=>'MX',
				'65'=>'484',
				'19'=>$stan,
				'47'=>$this->__generateSubcampo47($data),
			));
			#pr($dataText);

			$script = APP."vendors".DS."smart_connector".DS."encrypt.exe";
			#pr($data);
			$exec = sprintf(
				'mono %s "00=%s:37=%s:01=%s:04=%s" "%s" "%s" "%s" "%s"',
				$script,
				$data['number'],
				$data['name'],
				$data['exp'],
				$data['cvv'],
				$this->__getLastServerKey(),
				$stan,
				$time,
				$this->settings['randomKey']
			);
			#pr($exec);
			#pr($this->__getLastServerKey());
			$dataCipher = exec($exec);
			#pr($dataCipher);

			$xmlString = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
			<sbt-ws-message version=\"1.0\">
			    <header>
			        <Type>030100</Type>
			        <ClientID>{$this->settings['clientID']}</ClientID>
			        <SerialPos>{$this->settings['clientPOS']}</SerialPos>
			        <Stan>$stan</Stan>
			        <DeviceTime>$time</DeviceTime>
			    </header>
			    <message>
			        <DataCipher><![CDATA[$dataCipher)]]></DataCipher>
			        <DataText><![CDATA[$dataText]]></DataText>
			    </message>
			</sbt-ws-message>";

			pr(h($xmlString));
			#exit;
			$this->log("[Payment] Request: ".json_encode(array(
				'Type'=>'030100',
				'ClientID'=>$this->settings['clientID'],
				'SerialPos'=>$this->settings['clientPOS'],
				'Stan'=>$stan,
				'DeviceTime'=>$time,
			)),"SmartConnector");
			try{
				$process = curl_init($this->settings['hosts']);
				curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
				curl_setopt($process, CURLOPT_HEADER, 0);
				curl_setopt($process, CURLOPT_TIMEOUT, 30);
				curl_setopt($process, CURLOPT_POST, 1);
				curl_setopt($process, CURLOPT_POSTFIELDS, $xmlString);
				curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
				$return = curl_exec($process);
				$xml = new Xml($return);
				$xmlData = $xml->toArray();
				curl_close($process);
				#pr($xmlData);
				#$this->log($xmlData,"SmartConnector");
				#$this->__saveStan($stan+1);
				#$xmlData = array();
				if(isset($xmlData['Sbt-ws-message']['Header']['Resp-Code'])){
					$this->__saveLastStan($stan);
					$this->__saveCurrentStan($stan+1);
					switch($xmlData['Sbt-ws-message']['Header']['Resp-Code']){
						case '00':
							$this->log("[Payment] Response: ".json_encode($xmlData['Sbt-ws-message']['Header'])." | ".json_encode($xmlData['Sbt-ws-message']['Message']),"SmartConnector");
							setTimezoneByOffset(-7);
							return $xmlData['Sbt-ws-message']['Message'];
							break;
						default:
							$messageJSON = isset($xmlData['Sbt-ws-message']['Message']) ? json_encode($xmlData['Sbt-ws-message']['Message']) : "";
							$this->log("[Payment] Response Error: ".json_encode($xmlData['Sbt-ws-message']['Header'])." | ".$messageJSON,"SmartConnector");
							break;
					}
					setTimezoneByOffset(-7);
					return array(
						'error'=>true,
						'message'=>$xmlData['Sbt-ws-message']['Header']['Resp-Message'],
						'code'=>$xmlData['Sbt-ws-message']['Header']['Resp-Code']
					);
				}else{
					$this->log("[Payment] Response Error: No hubo respuesta del servidor de smart","SmartConnector");
					setTimezoneByOffset(-7);
					return array(
						'error'=>true,
						'message'=>"No hubo respuesta del servidor de smart",
						'code'=>"-1"
					);
				}
			}catch (Exception $e){
				$this->log("[Payment] Response Error: ".$e->getMessage(),"SmartConnector");
				setTimezoneByOffset(-7);
				return array(
					'error'=>true,
					'message'=>$e->getMessage(),
					'code'=>"-1"
				);
			}

		}
		return true;
	}

	function cancel($data){
		if($this->login()){
			$stan = $this->__getStan();
			#pr("stan $stan");
			setTimezoneByOffset(-6);

			$time = date('dmYHis', time());
			//date_default_timezone_set("UTC");
			$dataText = $this->__buildDataText(array(
				//'05'=>$data['total'],
				//'06'=>'1',
				//'07'=>'0',
				//'08'=>'0',
				//'09'=>'0',
				'16'=>'01',
				'17'=>'08',
				'64'=>'MX',
				'65'=>'484',
				//'19'=>$stan
			));
			#pr($dataText);

			$script = APP."vendors".DS."smart_connector".DS."encrypt.exe";
			#pr($data);
			$exec = sprintf(
				'mono %s "00=%s:37=%s:01=%s:04=%s" "%s" "%s" "%s" "%s"',
				$script,
				$data['number'],
				$data['name'],
				$data['exp'],
				$data['cvv'],
				$this->__getLastServerKey(),
				$stan,
				$time,
				$this->settings['randomKey']
			);
			#pr($exec);
			$dataCipher = exec($exec);
			#pr($dataCipher);

			$xmlString = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
			<sbt-ws-message version=\"1.0\">
			    <header>
			        <Type>030700</Type>
			        <ClientID>{$this->settings['clientID']}</ClientID>
			        <SerialPos>{$this->settings['clientPOS']}</SerialPos>
			        <Stan>$stan</Stan>
			        <DeviceTime>$time</DeviceTime>
			    </header>
			    <message>
			        <DataCipher><![CDATA[$dataCipher)]]></DataCipher>
			        <DataText><![CDATA[$dataText]]></DataText>
			        <RefSPNum>{$data['RefSPNum']}</RefSPNum>
			    </message>
			</sbt-ws-message>";
			$this->log("[Cancel] Request: ".json_encode(array(
				'Type'=>'030700 (cancelacion)',
				'ClientID'=>$this->settings['clientID'],
				'SerialPos'=>$this->settings['clientPOS'],
				'Stan'=>$stan,
				'DeviceTime'=>$time,
			)),"SmartConnector");
			try{
				$process = curl_init($this->settings['hosts']);
				curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
				curl_setopt($process, CURLOPT_HEADER, 0);
				curl_setopt($process, CURLOPT_TIMEOUT, 30);
				curl_setopt($process, CURLOPT_POST, 1);
				curl_setopt($process, CURLOPT_POSTFIELDS, $xmlString);
				curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
				$return = curl_exec($process);
				$xml = new Xml($return);
				$xmlData = $xml->toArray();
				curl_close($process);
				#pr($xmlData);
				$this->__saveStan($stan+1);
				if(isset($xmlData['Sbt-ws-message']['Header']['Resp-Code'])){
					$this->__saveLastStan($stan);
					$this->__saveCurrentStan($stan+1);
					switch($xmlData['Sbt-ws-message']['Header']['Resp-Code']){
						case '00':
							$this->log("[Cancel] Request: ".json_encode($xmlData['Sbt-ws-message']['Header'])." | ".json_encode($xmlData['Sbt-ws-message']['Message']),"SmartConnector");
							setTimezoneByOffset(-7);
							return $xmlData['Sbt-ws-message']['Message'];
							break;
						default:
							$this->log("[Cancel] Request Error: ".json_encode($xmlData['Sbt-ws-message']['Header']),"SmartConnector");
							break;

					}
					setTimezoneByOffset(-7);
					return array(
						'error'=>true,
						'message'=>$xmlData['Sbt-ws-message']['Header']['Resp-Message'],
						'code'=>$xmlData['Sbt-ws-message']['Header']['Resp-Code']
					);
				}else{
					$this->log("[Cancel] Request Error: No hubo respuesta del servidor de smart","SmartConnector");
					setTimezoneByOffset(-7);
					return array(
						'error'=>true,
						'message'=>"No hubo respuesta del servidor de smart",
						'code'=>"-1"
					);
				}
			}catch(Exception $e){
				$this->log("[Cancel] Error: ".$e->getMessage(),"SmartConnector");
				setTimezoneByOffset(-7);
				return array(
					'error'=>true,
					'message'=>$e->getMessage(),
					'code'=>"-1"
				);
			}
		}
		return true;
	}

	/**
	 * Funcion usada para cancelar la transacción cuando no se esta seguro si se realizo o falla el servidor de Vista
	 * @param     $data información del pago
	 * @param int $motivo Motivo por el que se esta cancelando la transaccion
	 * Los valores esperados son:
	 * 0: Reverso por time-out
	 * 1: Reverso por que el chip deniega la transacción
	 * 2: Falla en el punto de venta al concluir la transacción
	 *
	 * @return array|bool
	 */

	function reverse($data,$motivo = 0,$stan = false){
		if($this->login()){
			if(!$stan){
				$stan = $this->__getStan();
			}

			//if(isset($this->out)){
				//$this->out($stan);
			//}

			#pr("stan $stan");
			setTimezoneByOffset(-6);

			$time = date('dmYHis', time());
			//date_default_timezone_set("UTC");
			$dataText = $this->__buildDataText(array(
				'05'=>$data['total'],
				'21'=>$motivo,
				'64'=>'MX',
				'65'=>'484',
				//'19'=>$stan
			));
			#pr($dataText);

			$script = APP."vendors".DS."smart_connector".DS."encrypt.exe";
			#pr($data);
			$exec = sprintf(
				'mono %s "00=%s" "%s" "%s" "%s" "%s"',
				$script,
				$data['number'],
				$this->__getLastServerKey(),
				$stan,
				$time,
				$this->settings['randomKey']
			);
			#pr($exec);
			$dataCipher = exec($exec);
			#pr($dataCipher);

			$xmlString = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
			<sbt-ws-message version=\"1.0\">
			    <header>
			        <Type>030800</Type>
			        <ClientID>{$this->settings['clientID']}</ClientID>
			        <SerialPos>{$this->settings['clientPOS']}</SerialPos>
			        <Stan>$stan</Stan>
			        <DeviceTime>$time</DeviceTime>
			    </header>
			    <message>
			        <DataCipher><![CDATA[$dataCipher)]]></DataCipher>
			        <DataText><![CDATA[$dataText]]></DataText>
			    </message>
			</sbt-ws-message>";
			$this->log("[Reverse] Request: ".json_encode(array(
				'Type'=>'030800',
				'ClientID'=>$this->settings['clientID'],
				'SerialPos'=>$this->settings['clientPOS'],
				'Stan'=>$stan,
				'DeviceTime'=>$time,
			)),"SmartConnector");
			try{
				$process = curl_init($this->settings['hosts']);
				curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
				curl_setopt($process, CURLOPT_HEADER, 0);
				curl_setopt($process, CURLOPT_TIMEOUT, 30);
				curl_setopt($process, CURLOPT_POST, 1);
				curl_setopt($process, CURLOPT_POSTFIELDS, $xmlString);
				curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
				$return = curl_exec($process);
				$xml = new Xml($return);
				$xmlData = $xml->toArray();
				curl_close($process);
				#pr($xmlData);
				#$this->log($xmlData,"SmartConnector");
				#$xmlData = array();
				if(isset($xmlData['Sbt-ws-message']['Header']['Resp-Code'])){
					$this->__saveLastStan($stan);
					$this->__saveCurrentStan($stan+1);
					switch($xmlData['Sbt-ws-message']['Header']['Resp-Code']){
						case '00':
							$this->log("[Reverse] Response: ".json_encode($xmlData['Sbt-ws-message']['Header'])." | ".json_encode($xmlData['Sbt-ws-message']['Message']),"SmartConnector");
							return $xmlData['Sbt-ws-message']['Header']['Resp-Code'];
							break;
						default:
							$messageJSON = isset($xmlData['Sbt-ws-message']['Message']) ? json_encode($xmlData['Sbt-ws-message']['Message']) : "";
							$this->log("[Reverse] Response Error: ".json_encode($xmlData['Sbt-ws-message']['Header'])." | ".$messageJSON,"SmartConnector");
							break;

					}


					Cache::set(array('duration' => '+1 day'));
					$transactions = Cache::read("reverse_transactions");
					$transactions[] = array(
						'attempts' => 1,
						'data'=>$data,
						'time'=>time(),
						'last_attempt'=>time(),
						'motivo'=>$motivo,
						'working'=>false,
						'stan'=>$stan,
					);
					Cache::write("reverse_transactions",$transactions);
					setTimezoneByOffset(-7);
					return array(
						'error'=>true,
						'message'=>$xmlData['Sbt-ws-message']['Header']['Resp-Message'],
						'code'=>$xmlData['Sbt-ws-message']['Header']['Resp-Code']
					);
				}else{
					$this->log("[Reverse] Response Error: No hubo respuesta del servidor de smart","SmartConnector");
					/*if(!$nested){
						$this->log("Intento reverso (2)","SmartConnector");
						return  $this->reverse($data,$motivo,true);
					}*/

					Cache::set(array('duration' => '+1 day'));
					$transactions = Cache::read("reverse_transactions");
					$transactions[] = array(
						'attempts' => 1,
						'data'=>$data,
						'time'=>time(),
						'last_attempt'=>time(),
						'motivo'=>$motivo,
						'working'=>false,
						'stan'=>$stan,
					);
					Cache::set(array('duration' => '+1 day'));
					Cache::write("reverse_transactions",$transactions);

					setTimezoneByOffset(-7);
					return array(
						'error'=>true,
						'message'=>"No hubo respuesta del servidor de smart",
						'code'=>"-1"
					);
				}
			}catch(Exception $e){
				$this->log("[Reverse] Response Error: ".$e->getMessage(),"SmartConnector");
				setTimezoneByOffset(-7);
				return array(
					'error'=>true,
					'message'=>$e->getMessage(),
					'code'=>"-1"
				);
			}
		}
		return true;
	}

	function __generateSubcampo47($data){
		$_browser = detectBrowser();
		$browser = $_browser['browser']."v".$_browser['version']." ".$_browser['os'];
		$hostname = "server";
		$country = "MX";
		$metodo_envio = "0";
		$sku = " ";
		$ip = env('REMOTE_ADDR');
		$tel = " ";
		$codigo_tel = " ";
		return sprintf("%s|%s|%s|%s|%s|%s|%s|%s|%s",$data['email'],$browser,$hostname,$country,$metodo_envio,$sku,$ip,$tel,$codigo_tel);
	}

	function __buildDataText($data){
		$dataText = "";
		foreach($data as $key=>$val){
			if(strlen($dataText) > 0){
				$dataText .="\r\n";
			}
			$dataText.=$key."=".$val;
		}
		return $dataText;
	}

	function __getPubKey(){
		$filename = APP."vendors".DS."smart_connector".DS."sample1_pub.pem";
		$fp=fopen($filename,"r");
		$pubkey=fread($fp,filesize($filename)); // Saque el tamaño del archivo y lo pase como parametro
		fclose($fp);
		return $pubkey;
	}

	function __getStan(){
		/*$cached = Cache::read("smart_connector");
		return isset($cached['stan']) ? $cached['stan'] : 1;*/
		return Configure::read("AppConfig.smart_current_stan");
	}

	function __saveCurrentStan($val){
		$this->Setting->save(array(
			'id'=>18, #id del campo smart_current_stan
			'value'=>$val
		));
	}

	function __saveLastStan($val){
		$this->Setting->save(array(
			'id'=>17, #id del campo smart_current_stan
			'value'=>$val
		));
	}

	function __isLogged(){
		$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
		return date("Y-m-d",Configure::read("AppConfig.smart_login_date")) == date("Y-m-d",$today);
	}

	function __getLastServerKey(){
		/*Cache::set(array('duration' => '+30 days'));
		$cached = Cache::read("smart_connector");*/
		#return isset($cached['lastServerKey'])? $cached['lastServerKey'] : false;
		return  Configure::read("AppConfig.smart_lastServerKey");

	}

}
?>