<?php
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
 * @property $Settings Settings
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
		'Show',
		'Setting',
	);

	var $locationsNoScheduled = array();

	var $locationsFail = array();

	var $errors = array();

	var $projectionsNotFound = array();

	var $config = array();

	function startUp(){
		$this->Dispatch->clear();
	}

	/**
	 * Metodo que se ejecuta automaticamente desde el comando de consola
	 */
	function main(){
		if($this->__sincronizar()){
			$this->hr(1);
			$this->out("Bienvenido a la sincronización de carteleras");
			$this->out("Fecha: ".date("F j, Y, h:i:s a"));
			$this->hr(1);

			try{
				$starDate = mktime(0,0,0,date("m"),date("d"),date("Y"));
				$endDate = mktime(23,59,59,date("m"),date("d"),date("Y")+1);
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
						$this->hr(1);
						$this->out("Complejo: ".$record['Location']['name']);
						$this->out("- Conectando: ".$record['Location']['vista_service_url']);

						$connection = false;
						try {
							$this->VistaServer = @new SoapClient($record['Location']['vista_service_url'],array('cache_wsdl'=>WSDL_CACHE_NONE));
							$connection = true;
						} catch (Exception $e) {
							$this->locationsFail[] = $record['Location']['id'];
							$this->errors['locations_connection'][] = $record['Location'];
							$this->err($e->getMessage());
						}

						if($connection){
							$this->out("-- Ok ");
							$this->__UpdateBillboard($record['Location'],$starDate,$endDate);
						}
					}
				}
			}catch(Exception $e){
				$this->errors['exec'] = $e->getMessage();
				$this->err($e->getMessage());
			}

			if(!empty($this->errors)){
				#Enviar notificacion de los errores por mail
				$this->out("Sending");
				$this->__sendNotification();
			}

			#guardar el resultado de la sincronizacion en cache
			$syncStatus['fail'] = !empty($this->errors);
			$syncStatus['date'] = date("Y-m-d H:i:s");
			$syncStatus['locations_fail'] = implode("|",$this->locationsFail);
			$syncStatus['locations_no_scheduled'] = implode("|",$this->locationsNoScheduled);
			$syncStatus['projections_not_found'] = implode("|",$this->projectionsNotFound);

			Cache::write("sync_billboard_status",$syncStatus);

			$this->hr(1);
			$this->out("Fin de la ejecución: ".date("F j, Y, h:i:s a"));
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
			$this->locationsFail[] = $location['id'];
			$this->errors['locations_connection'][] = $location;

			$this->err($e->getMessage());
		}
		$r = explode("|",$response->ReturnData);
		App::Import('Core','Xml');

		try{
			$xml = new Xml($r[6]);
			$data = $xml->toArray();
		}catch (Exception $e){
			$this->locationsFail[] = $location['id'];
			$this->errors['xml'][] = $location;

			$this->err($e->getMessage());
		}


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
					$this->projectionsNotFound[] =  $session['Film_strCode'].">".$session['Film_strTitle'];
					$this->errors['projections_not_found'][$session['Film_strCode']] = $session['Film_strTitle'];
				}

			}

			$this->Show->commit();

		}else{
			# Error: No se encontraron horarios
			$$this->locationsNoScheduled[] = $location['id'];
			$this->errors['location_no_scheduled'][] = $location;
		}

	}

	function __sendNotification(){
		App::import('Core', 'Controller');
		App::import('Controller', 'App');
		$Controller = & new Controller();

		App::import('Component', 'Email');
		$Email = new EmailComponent();
		$Email->initialize($Controller);

		$Email->reset();
		$Email->to = $this->config['sync_error_email'];
		$Email->from = "erochin@h1webstudio.com";
		$Email->subject = "Errores en la sincronización de la cartelera";
		$Email->sendAs = 'html';
		$Controller->set("errors",$this->errors);
		$Email->template = "sync_error";

		/* Opciones SMTP*/
		/*$Email->smtpOptions = array(
			'port'=>'25',
			'timeout'=>'30',
			'host' => 'mail.h1webstudio.com',
			'username'=>'erochin@h1webstudio.com',
			'password'=>'Rochin12!-');

		$Email->delivery = 'smtp';*/

		$Email->send();

		#$this->out(print_r($Email->smtpError));

	}

	function __sincronizar(){
		$this->config = $this->Setting->getConfig();
		$syncStatus = Cache::read("sync_billboard_status");
		if(mktime($this->config['sync_hour'],0,0,date("m"),date("d"),date("Y")) <= strtotime("now")){
			return true;
		}
		#$this->out(date("Y-m-d H:i:s",strtotime($syncStatus['date']))." - ".date("Y-m-d H:i:s",strtotime("-".$this->config['sync_error_interval']." min")));
		if(!empty($syncStatus) && $syncStatus['fail'] && strtotime($syncStatus['date']) <= strtotime("-".$this->config['sync_error_interval']." min")){
			return true;
		}

		return empty($syncStatus);

	}

}
?>