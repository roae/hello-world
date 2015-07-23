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
 * @property $Setting Setting
 * @property $Room Room
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
		'Room'
	);

	var $locationsNoScheduled = array();

	var $locationsFail = array();

	var $errors = array();

	var $projectionsNotFound = array();

	var $config = array();

	var $syncStatus = array();

	function startUp(){
		$this->Dispatch->clear();
	}

	/**
	 * Verifica si se debe ejecutar la sincronizacion
	 * @return bool
	 */
	function __sincronizar(){
		$this->config = $this->Setting->getConfig();
		$syncStatus = Cache::read("sync_billboard_status");

		if($syncStatus['running']){
			$this->out("Running");
			return false;
		}
		$this->syncStatus = $syncStatus;
		#$this->out(print_r($syncStatus));
		#$this->hr();
		#$this->out(date("Y-m-d H:i:s",mktime($this->config['sync_hour'],0,0,date("m"),date("d"),date("Y")))." - ".date("Y-m-d H:i:s",strtotime("now")));
		/*$sync_hour = mktime($this->config['sync_hour'],0,0,date("m"),date("d"),date("Y"));
		if(($sync_hour <= strtotime("now")) && mktime($this->config['sync_hour'],0,0,date("m"),date("d"),date("Y")) > strtotime($syncStatus['date'])){
			return true;
		}*/
		#$this->out(date("Y-m-d H:i:s",strtotime($syncStatus['date']))." - ".date("Y-m-d H:i:s",strtotime("-".$this->config['sync_error_interval']." min")));
		if(!empty($syncStatus) && $syncStatus['fail'] && strtotime($syncStatus['date']) <= strtotime("-".$this->config['sync_interval']." min")){
			return true;
		}

		return empty($syncStatus);

	}

	function __getArgLocation(){
		return isset($this->args[0])? $this->args[0] : null;
	}

	/**
	 * Metodo que se ejecuta automaticamente desde el comando de consola
	 */
	function main(){
		$this->out("Fecha: ".date("F j, Y, h:i:s a"));
		$location_id = $this->__getArgLocation();

		if($this->__sincronizar() || $location_id){
			$this->hr(1);
			$this->out("Bienvenido a la sincronización de carteleras");
			$this->out("Fecha: ".date("F j, Y, h:i:s a"));
			#$this->hr(1);
			//$this->syncStatus = array();
			$this->syncStatus['fail'] = false;
			$this->syncStatus['date'] = date("Y-m-d H:i:s");
			$this->syncStatus['running'] = true;

			try{
				$starDate = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
				$endDate = mktime(23,59,59,date("m"),date("d"),date("Y")+1);
				#$this->out("Periodo: ".date("Y-m-d H:i:s",$starDate)." | ".date("Y-m-d H:i:s",$endDate));

				$conditions = ($location_id == "all")? array() : array('Location.id'=>$location_id);
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
					),
					'conditions'=>$conditions
				));

				foreach((array) $locations as $record){

					$this->syncStatus['locations'][$record['Location']['id']]=array(
						'fail'=>false,
						'date'=>date("Y-m-d H:i:s"),
						'id'=>$record['Location']['id'],
						'connection'=>true,
						'scheduled'=>true,
						'manual'=>!empty($location_id),
						'running'=>true
					);

					$this->__cacheSyncStatus();

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

							$this->syncStatus['fail'] = true;
							$this->syncStatus['locations'][$record['Location']['id']]['fail'] = true;
							$this->syncStatus['locations'][$record['Location']['id']]['connection'] = false;
							$this->syncStatus['locations'][$record['Location']['id']]['running'] = false;
							$this->__cacheSyncStatus();
						}

						if($connection){
							$this->out("-- Ok ");
							#$this->__GetSessionDisplayData($record['Location'],$starDate,$endDate);
							$this->__GetSellingDataXMLStream($record['Location']);
						}
					}
				}
			}catch(Exception $e){
				$this->syncStatus['fail'] = true;
				$this->errors['exec'] = $e->getMessage();
				$this->err($e->getMessage());
			}

			if(!empty($this->errors)){
				#Enviar notificacion de los errores por mail
				$this->out("Enviando notificación a la dirección ".$this->config['sync_error_email']);
				$this->__sendNotification();
			}else{
				$this->out("Sincronización sin errores");
			}

			#guardar el resultado de la sincronizacion en cache

			$this->syncStatus['running'] = false;
			$this->syncStatus['projections_not_found'] = isset($this->errors['projections_not_found'])? $this->errors['projections_not_found'] : array();
			$this->syncStatus['exec_errors'] = isset($this->errors['exec'])? $this->errors['exec'] : array();

			$this->__cacheSyncStatus();

			$this->hr(1);
			$this->out("Fin de la ejecución: ".date("F j, Y, h:i:s a"));
			$this->hr(1);
		}

		$this->_stop(1);

	}

	/**
	 * Obtiene todas las peliculas que se muestran en cada complejo y las guarda en la tabla playings
	 * Usando la función GetSellingDataXMLStream (en esta funcion viene la información de precios)
	 * @param $location Array Información del complejo
	 */
	function __GetSellingDataXMLStream($location){
		$params = array(
			'ClientID'=>'WEB',
			'TransIdTemp'=>"".rand(0,10000000),
			'CmdName'=>'GetSellingDataXMLStream',
			'Param1'=>"PRICES|FILMS", # PRICES es el bueno
			'Param2'=>"".(30*24), #TODO: poner la variable de configuracion de los dias
			'Param3'=>"",
			'Param4'=>"",
			'Param5'=>"",
			'Param6'=>""
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

			$this->syncStatus['fail'] = true;
			$this->syncStatus['locations'][$location['id']]['fail'] = true;
			$this->syncStatus['locations'][$location['id']]['connection']=false;
		}
		#$this->out(print_r($response->ReturnData));
		$r = explode("|",$response->ReturnData);
		App::Import('Core','Xml');

		try{
			#$this->out($r[6]);
			$xml = new Xml($r[6]);
			$data = $xml->toArray();
			#$this->out("XML to Array");
			#$this->log($data,"GetSellingDataXMLStream");
		}catch (Exception $e){
			$this->locationsFail[] = $location['id'];
			$this->errors['xml'][] = $location;
			$this->err($e->getMessage());
			$this->syncStatus['locations'][$location['id']]['fail'] = true;
			#$this->syncStatus[$location['id']]['connection']=false;
		}

		$movies = array();
		#$this->log($location,'sync')
		#print_r($data);
		if(isset($data['VistaData']['Sessions']) && !empty($data['VistaData']['Sessions'])){
			#$this->out("VistaData exist");
			//$this->out("rochin");
			$this->Show->begin();
			$idSessions = Set::classicExtract($data['VistaData']['Sessions']['Session'],'{n}.Session_ID');
			//$this->out(print_r($idSessions));
			$sessionsList = $this->Show->find("list",array('conditions'=>array('Show.location_id'=>$location['id']),'fields'=>array('Show.session_id')));

			/*$this->out($sessionsList);
			$this->hr();
			$this->out("SessionID");
			$this->out($idSessions);
			$this->hr();
			$this->out("DIFF");
			$this->out(Set::diff($sessionsList,$idSessions));*/
			$added  = array();
			foreach($idSessions as $sessionID){
				if($key = array_search($sessionID,$sessionsList)){
					$added[$key] = $sessionID;
				}
			}

			$this->Show->deleteAll(array('Show.location_id'=>$location['id'],'Show.session_id NOT'=>$idSessions));

			foreach($data['VistaData']['Sessions']['Session'] as $session){
				$showID = array_search($session['Session_ID'],$added);
				/*if($showID){

				}*/
				#$this->out(print_r(Set::extract('/Movie[Movie_ID=/'.$session['Movie_ID'].'/i]',$data['VistaData']['Movies'])));
				$movie = Set::extract("/Movie[Movie_ID=/{$session['Movie_ID']}/i]",$data['VistaData']['Movies']);
				if(isset($movie[0]['Movie'])){
					$movies[$session['Movie_ID']] = $movie[0]['Movie']['Movie_Name'];
				}

				$year = substr($session['Date_time'],0,4);
				$month= substr($session['Date_time'],4,2);
				$day = substr($session['Date_time'],6,2);
				$hours = substr($session['Date_time'],8,2);
				$mins = substr($session['Date_time'],10,2);
				$seconds = substr($session['Date_time'],12,2);
				#usleep(80000);
				$this->out($session['Cinema_ID']." | ".$session['Movie_ID']. " - ".$movies[$session['Movie_ID']]." |".$year."-".$month."-".$day." ".$hours.":".$mins."| ".$session['Screen_strName']);

				# Se Obtiene el id de la projeccion y el id de la pelicula por el campo Projection.vista_code [Film_strCode]
				$projection=$this->Projection->find("first",array(
					'conditions'=>array('Projection.vista_code'=>$session['Movie_ID']),
					'fields'=>array('Projection.id'),
					'contain'=>array(
						'Movie'=>array(
							'fields'=>array('Movie.id')
						)
					)
				));

				if(!empty($projection)){
					$roomType = $this->Room->find("first",array(
						'fields'=>array(
							'Room.id',
							'Room.room_type'
						),
						'conditions'=>array(
							'Room.description'=>$session['Screen_strName'],
							'Room.location_id'=>$location['id']
						)
					));
					#$this->log($roomType,"roomtype");
					if(!isset($roomType['Room']['id'])){
						#$this->log("rochin","roomtype");
						$this->Room->create();
						$this->Room->save(array('Room'=>array(
							'description'=>$session['Screen_strName'],
							'location_id'=>$location['id'],
							'status'=>1,
							'trash'=>0
						)));

						#$this->log($this->Room->validationErrors,"roomtype");
					}
					//$this->log($roomType,"roomtype");
					$show = array(
						'location_id'=>$location['id'],
						'movie_id'=>$projection['Movie']['id'],
						'projection_id'=>$projection['Projection']['id'],
						'schedule'=>"{$year}-{$month}-{$day} $hours:$mins:$seconds",
						'sales_channels'=> str_replace("^~^","|",$session['Session_strSalesChannels']),
						'session_id'=> $session['Session_ID'],
						'screen_name'=>$session['Screen_strName'],
						'room_type'=> $roomType['Room']['room_type'],
						'seat_alloctype'=>$session['Seat_allocation_on'] == "Y",
					);


					if(!$showID){
						$this->Show->create();
					}else{
						$show['id']=$showID;
					}

					if($this->Show->save($show)){ # si ya existe la session, no se guarda el show, solo se actualizan los precios
						if(!$showID) {
							$showID = $this->Show->id;
						}
						if($showID){
							$session_prices = Set::extract("/Price[Price_group_code=/{$session['Price_group_code']}/]",$data['VistaData']['Prices']);
							$ticketsAdded = Set::classicExtract($session_prices,"{n}.Price.Ticket_type_code");
							$tickets = $this->Show->TicketPrice->find("list",array('conditions'=>array('TicketPrice.show_id'=>$showID),'fields'=>array('TicketPrice.code')));

							$this->Show->TicketPrice->deleteAll(array('TicketPrice.show_id'=>$location['id'],'TicketPrice.code NOT'=>$ticketsAdded));

							foreach($session_prices as $record){
								if(strpos($record['Price']['TType_strSalesChannels'],"WWW")){
									$ticketID = array_search($record['Price']['Ticket_type_code'],$tickets);
									if(!$ticketID){
										$this->Show->TicketPrice->create();
										$ticketID = null;
									}

									#$this->out($record['Price']['Ticket_Price']);
									#$this->out($record['Price']['Ticket_Price']/100);
									$this->Show->TicketPrice->save(array(
										'id'=>$ticketID,
										'show_id'=>$showID,
										'code'=>$record['Price']['Ticket_type_code'],
										'description'=>$record['Price']['Ticket_type_description'],
										'price'=>$record['Price']['Ticket_Price']/100*1.0
									));
								}
							}
						}
					}else{
						$this->out("Error: No se guardo el horario");
						$this->out(print_r($show));
						$this->out(print_r($this->Show->invalidFields()));
					}
				}else{
					$this->out("- El código ".$session['Movie_ID']." no se ha asignado a una pelicula");
					$this->syncStatus['fail'] = true;
					$this->syncStatus['locations'][$location['id']]['fail'] = true;
					$this->syncStatus['locations'][$location['id']]['projections_not_found']=true;
					$this->projectionsNotFound[] =  $session['Movie_ID'].">".$movies[$session['Movie_ID']];
					$this->errors['projections_not_found'][$session['Movie_ID']] = $movies[$session['Movie_ID']];
				}

			}

			$this->Show->commit();

		}else{
			# Error: No se encontraron horarios
			$this->out("No se descargaron horarios en este complejo");
			$this->locationsNoScheduled[] = $location['id'];
			$this->errors['location_no_scheduled'][] = $location;

			$this->syncStatus['locations'][$location['id']]['fail'] = true;
			$this->syncStatus['locations'][$location['id']]['scheduled']=false;
			$this->syncStatus['fail'] = true;
		}

		$this->syncStatus['locations'][$location['id']]['running'] = false;

	}

	/**
	 * Obtiene todas las peliculas que se muestran en cada complejo y las guarda en la tabla playings
	 * Usando la funcion GetSessionDisplayData del servicio
	 * @param $location Array Informacion del complejo
	 * @param $starDate DateTime
	 * @param $endDate DateTime
	 */
	function __GetSessionDisplayData($location,$starDate,$endDate){
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

			$this->syncStatus['fail'] = true;
			$this->syncStatus['locations'][$location['id']]['fail'] = true;
			$this->syncStatus['locations'][$location['id']]['connection']=false;
		}
		$r = explode("|",$response->ReturnData);
		App::Import('Core','Xml');

		try{
			$this->out($r[6]);
			$xml = new Xml($r[6]);
			$data = $xml->toArray();
			#$this->log($data,"GetSessionDisplayData");
		}catch (Exception $e){
			$this->locationsFail[] = $location['id'];
			$this->errors['xml'][] = $location;
			$this->err($e->getMessage());
			$this->syncStatus['locations'][$location['id']]['fail'] = true;
			#$this->syncStatus[$location['id']]['connection']=false;
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
					$roomType = $this->Room->find("first",array(
						'fields'=>array(
							'Room.id',
							'Room.room_type'
						),
						'conditions'=>array(
							'Room.description'=>$session['Screen_strName'],
							'Room.location_id'=>$location['id']
						)
					));
					#$this->log($roomType,"roomtype");
					if(!isset($roomType['Room']['id'])){
						#$this->log("rochin","roomtype");
						$this->Room->create();
						$this->Room->save(array('Room'=>array(
							'description'=>$session['Screen_strName'],
							'location_id'=>$location['id'],
							'status'=>1,
							'trash'=>0
						)));

						#$this->log($this->Room->validationErrors,"roomtype");
					}
					//$this->log($roomType,"roomtype");
					$show = array(
						'location_id'=>$location['id'],
						'movie_id'=>$projection['Movie']['id'],
						'projection_id'=>$projection['Projection']['id'],
						'schedule'=>"{$year}-{$month}-{$day} $hours:$mins:$seconds",
						'sales_channels'=> str_replace("^~^","|",$session['Session_strSalesChannels']),
						'session_id'=> $session['Session_lngSessionId'],
						'screen_name'=>$session['Screen_strName'],
						'room_type'=> $roomType['Room']['room_type'],
						'seat_alloctype'=>$session['Session_bytSeatAllocType'],
					);
					$this->Show->create();
					if(!$this->Show->save($show)){
						$this->out("Error: No se guardo el horario");
						$this->out(print_r($show));
						$this->out(print_r($this->Show->invalidFields()));
					}
				}else{
					$this->out("- El código ".$session['Film_strCode']." no se ha asignado a una pelicula");
					$this->syncStatus['fail'] = true;
					$this->syncStatus['locations'][$location['id']]['fail'] = true;
					$this->syncStatus['locations'][$location['id']]['projections_not_found']=true;
					$this->projectionsNotFound[] =  $session['Film_strCode'].">".$session['Film_strTitle'];
					$this->errors['projections_not_found'][$session['Film_strCode']] = $session['Film_strTitle'];
				}

			}

			$this->Show->commit();

		}else{
			# Error: No se encontraron horarios
			$$this->locationsNoScheduled[] = $location['id'];
			$this->errors['location_no_scheduled'][] = $location;

			$this->syncStatus['locations'][$location['id']]['fail'] = true;
			$this->syncStatus['locations'][$location['id']]['scheduled']=false;
			$this->syncStatus['fail'] = true;
		}

		$this->syncStatus['locations'][$location['id']]['running'] = false;

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
		$Email->layout="notifications";
		$Email->template = "sync_error";

		/* Opciones SMTP*
		$Email->smtpOptions = array(
			'port'=>'25',
			'timeout'=>'30',
			'host' => 'mail.h1webstudio.com',
			'username'=>'erochin@h1webstudio.com',
			'password'=>'Rochin12!-');

		$Email->delivery = 'smtp';
		/**/
		$Email->send();

		$this->out(print_r($Email->smtpError));

	}

	function __cacheSyncStatus(){
		$this->log($this->syncStatus,"sync");
		Cache::write("sync_billboard_status",$this->syncStatus);
	}



}
?>