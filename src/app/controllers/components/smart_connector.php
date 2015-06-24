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

	var $settings = array();

	var $headers = array(
		'Accept: application/xml',
		'Content-Type: application/xml; charset=UTF-8'
	);


	function initialize( &$controller, $settings=array() ) {
		$this->settings = am($this->settings, $settings);
	}


	function login( ){
		if(!$this->__isLogged()){
			$stan = $this->__getStan();
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
				//pr($xmlData);

				if($xmlData['Sbt-ws-message']['Header']['Resp-Code'] != "00"){
					return false;
				}else{
					$cache = array(
						'login_date'=>mktime(0,0,0,date("m"),date("d"),date("Y")),
						'lastServerKey'=>$xmlData['Sbt-ws-message']['Header']['LastServerKey'],
						'stan'=>2
					);
					Cache::set(array('duration' => '+30 days'));
					Cache::write("smart_connector",$cache);
					return true;
				}
			}catch (Exception $e){
				return false;
			}
		}else{
			return true;
		}



	}

	function payment($data){
		if($this->login()){
			$stan = $this->__getStan();
			#pr("stan $stan");
			//date_default_timezone_set("Mexico/General");
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
				'19'=>$stan
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
			if($xmlData['Sbt-ws-message']['Header']['Resp-Code'] == "00"){
				return $xmlData['Sbt-ws-message']['Message'];
			}else{
				return array(
					'error'=>true,
					'message'=>$xmlData['Sbt-ws-message']['Header']['Resp-Message'],
					'code'=>$xmlData['Sbt-ws-message']['Header']['Resp-Code']
				);
			}


		}
		return true;
	}

	function cancel($data){
		if($this->login()){
			$stan = $this->__getStan();
			#pr("stan $stan");
			//date_default_timezone_set("Mexico/General");
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
			/*if($xmlData['Sbt-ws-message']['Header']['Resp-Code'] == "00"){
				return $xmlData['Sbt-ws-message']['Message']['Aut-Code'];
			}else{
				return array(
					'error'=>true,
					'message'=>$xmlData['Sbt-ws-message']['Header']['Resp-Message'],
					'code'=>$xmlData['Sbt-ws-message']['Header']['Resp-Code']
				);
			}*/


		}
		return true;
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
		$cached = Cache::read("smart_connector");
		return isset($cached['stan']) ? $cached['stan'] : 1;
	}

	function __saveStan($val){

		Cache::set(array('duration' => '+30 days'));
		$cache = Cache::read("smart_connector");
		//pr($cache);
		$cache['stan']= $val;
		#pr($cache);

		Cache::write("smart_connector",$cache);
	}

	function __isLogged(){
		Cache::set(array('duration' => '+30 days'));
		$cached = Cache::read("smart_connector");
		//pr($cached);

		if(isset($cached['login_date'])){
			$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
			return $cached['login_date'] == $today;
		}else{
			return false;
		}
	}

	function __getLastServerKey(){
		Cache::set(array('duration' => '+30 days'));
		$cached = Cache::read("smart_connector");
		return isset($cached['lastServerKey'])? $cached['lastServerKey'] : false;

	}

}
?>