<?php
App::Import('Core','Xml');
App::Import("Component","Email");
/**
 * Class SyncShell
 *
 * Shell que sirve para actualizar la cartelera, este es llamado por medio de un CRON
 *
 * Modelos
 * @property $Location Location
 * @property $Movie Movie
 * @property $Projection Projection
 * @property $Show Show
 *
 * Components
 * @property $Email EmailComponent
 *
 */
class SyncShell extends Shell{
	/*
	 * SoapClient
	 */
	var $VistaServer;

	/**
	 * Models
	 * @var array
	 */
	var $uses = array(
		'Location',
		'Movie',
		'Projection',
		'Show'
	);

	function startUp(){
		$this->Dispatch->clear();
		$this->hr();
		$this->out("");
		$this->out("Bienvenido a la sincronización de carteleras");
		$this->out("Fecha: ".date("F j, Y, g:i:s a"));
		$this->out("");
		$this->hr();
	}

	/**
	 * Metodo que se ejecuta automaticamente desde el comando de consola
	 */
	function main(){
		try{
			$starDate = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$endDate = mktime(23,59,59,date("m"),date("d"),date("Y"));
			$this->out("Periodo: ".date("Y-m-d H:i:s",$starDate)." | ".date("Y-m-d H:i:s",$endDate));

			$locations = $this->Location->find("all",array(
				'conditions'=>array(
					'Location.status'=>1,
					'Location.trash'=>0
				),
				'fields'=>array(
					'Location.id',
					'Location.name',
					'Location.vista_code',
					'Location.vista_service_url',
				)
			));

			foreach((array) $locations as $record){
				if(!empty($record['Location']['vista_service_url'])){
					$this->hr();
					$this->out("");
					$this->out("Complejo: ".$record['Location']['name']);
					$this->out("- Conectando: ".$record['Location']['vista_service_url']);

					$connection = false;
					try {
						$this->VistaServer = @new SoapClient($record['Location']['vista_service_url'],array('cache_wsdl'=>WSDL_CACHE_NONE));
						$connection = true;
					} catch (Exception $e) {
						# TODO: Send Email
						$this->err($e->getMessage());
					}

					if($connection){
						$this->out("-- Ok ");
						$this->__UpdateBillboard($record['Location'],$starDate,$endDate);
					}
				}
			}
		}catch(Exception $e){
			$this->err($e->getMessage());
		}

		$this->_stop(1);

	}

	/**
	 * Obtiene todas las peliculas que se muestran en cada complejo y las guarda en la tabla playings
	 * @param $location Array Informacion del complejo
	 * @param $dateStart DateTime
	 * @param $dateEnd DateTime
	 */
	function __UpdateBillboard($location,$starDate,$endDate){
		$params = array(
			'ClientID'=>'WEB','TransIdTemp'=>"".rand(0,10000000),
			'CmdName'=>'GetSessionDisplayData',
			'Param1'=>"",
			'Param2'=>"|DATESTART|".date("YmdHi",$starDate)."|DATEEND|".date("YmdHi",$endDate)."|OPENONLY|N|",
			'Param3'=>"",'Param4'=>"",'Param5'=>"",'Param6'=>""
		);

		$this->out("- Obteniendo los datos de la cartelera");
		try{
			$response = $this->VistaServer->__soapCall("Execute",array($params));
			$this->out("-- Ok");
			$this->hr();
		}catch (Exception $e){
			# TODO: Send Email
			$this->err($e->getMessage());
		}
		$r = explode("|",$response->ReturnData);
		$xml = new Xml($r[6]);
		$data = $xml->toArray();
		#$this->log($location,'sync');
		if(isset($data['VSSSessionDisplayData']) && !empty($data['VSSSessionDisplayData'])){
			$this->Show->begin();
			$this->Show->deleteAll(array('Show.location_id'=>$location['id']));
			foreach($data['VSSSessionDisplayData']['S'] as $session){
				$year = substr($session['Session_dtmShowing'],0,4);
				$month= substr($session['Session_dtmShowing'],4,2);
				$day = substr($session['Session_dtmShowing'],6,2);
				$hours = substr($session['Session_dtmShowing'],8,2);
				$mins = substr($session['Session_dtmShowing'],10,2);
				$seconds = substr($session['Session_dtmShowing'],12,2);
				#usleep(80000);
				$this->out($session['CinOperator_strCode']." | ".$session['Film_strCode']. " - ".$session['Film_strTitle']." |".$year."-".$month."-".$day." ".$hours.":".$mins."| ".$session['Screen_strName']);

				# Se Obtiene el id de la projeccion y el id de la pelicula por el campo Projection.vista_code [Film_strCode]
				$projection=$this->Projection->find("first",array(
					'conditions'=>array('Projection.vista_code'=>$session['Film_strCode']),
					'fields'=>array('Projection.id'),
					'contain'=>array(
						'Movie'=>array(
							'fields'=>array('Movie.id')
						)
					)
				));

				if(!empty($projection)){
					$show = array(
						'location_id'=>$location['id'],
						'movie_id'=>$projection['Movie']['id'],
						'projection_id'=>$projection['Projection']['id'],
						'schedule'=>"{$year}-{$month}-{$day} $hours:$mins:$seconds"
					);
					$this->Show->create();
					if(!$this->Show->save($show)){
						$this->out("Error: No se guardo el horario");
						$this->out(print_r($show));
						$this->out(print_r($this->Show->invalidFields()));
					}
				}else{
					$this->out("- El código ".$session['Film_strCode']." no se ha asignado a una pelicula");
				}

			}

			$this->Show->commit();

		}
	}

}

/*$params = array(
			'ClientID'=>'209.126.208.164',
			'TransIdTemp'=>'12122121',
			'CmdName'=>'GetSellingDataXMLStream',
			'param1'=>'ALL',
			#'Param1'=>"ALL|SESSIONS|FILMS|PRICES|PRICESALL|PRICESINCLZERO | CONCESSIONS | CINEMAOPERATORS | CARDDEFINITIONS | PRICEPACKAGES | ITEMALT | BOM | CONCESSIONSINCLZERO | BOMINCLZERO | ITEMALTINCLZERO | CONCESSIONSALL | POSBUTTONS | CONCESSIONBUTTONS | CONCESSIONTABS | CURRENCYPAYMENTTYPES",
			'Param2'=>'',
			'Param3'=>"",
			'Param4'=>"",
			'Param5'=>"",
			'Param6'=>""
		);
		$response = $client->__soapCall("Execute",array($params));
		$r = explode("|",$response->ReturnData);
		$this->log($r[6],"server");
		#$xml = new Xml($r[6]);
		#$this->log($xml->toString(),"server");
		#$this->set("GetSellingDataXMLStream",$xml->toArray());


		$params = array(
			'ClientID'=>'209.126.208.164',
			'TransIdTemp'=>'12122121',
			'CmdName'=>'GetSessionDisplayData',
			'Param1'=>"",
			'Param2'=>'|DATESTART|201501270000|DATEEND|201501272359|OPENONLY|Y|MAXRECORDS| 0 | ATMONLY | N |',
			'Param3'=>"",
			'Param4'=>"",
			'Param5'=>"",
			'Param6'=>""
		);
		$response = $client->__soapCall("Execute",array($params));
		$r = explode("|",$response->ReturnData);
		#$this->log($r[6],"GetSessionDisplayData");
		$xml = new Xml($r[6]);
		$this->set("GetSessionDisplayData",$xml->toArray());
*/
?>