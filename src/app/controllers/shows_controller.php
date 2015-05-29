<?php

/**
 * Class ShowsController
 * @property $Show Show
 * @property $Buy Buy
 */
class ShowsController extends AppController{
	var $name = "Shows";
	var $uses = array(
		"Show",
		"Buy",
	);
	/**
	 * Conditions de peliculas usados para sacar los horarios
	 * @var array
	 */
	var $__showConditions = array();

	var $transIdTemp;

	var $VistaServer;

	function index() {
		if(!isset($this->params['slug'])){
			$this->cakeError("error404");
		}

		$this->__setCitySelected();
		$date = date("Y-m-d");
		//$start = date("Y-m-d h:i:s");
		$start = strtotime("-30 min");
		/*if($this->Session->read("BillboardFilter.date")){
			$date = $this->Session->read("BillboardFilter.date");
			$start = $date." ".date("H:i:s");
			if($date != date("Y-m-d")){
				$start = $date." 0:0:0";
			}
		}*/
		if(isset($this->params['named']['date'])){
			$date = $this->params['named']['date'];
			$start = $date." ".date("H:i:s");
			if($date != date("Y-m-d")){
				$start = $date." 0:0:0";
			}
		}
		$end = $date." 23:59:59";
		$this->__showConditions = array(
			'Show.schedule >='=>$start,
			'Show.schedule <='=>$end,
		);

		$this->set("billboard",$this->__getBillboardSchedules());

	}

	function __getBillboardSchedules(){
		$recordset = $this->Show->Location->find("all", array(
			'fields'=>$this->Show->Location->publicFields,
			'conditions'=>array(
				'Location.trash'=>0,
				'Location.status'=>1,
				'Location.city_id'=>Configure::read("CitySelected.id"),
			),
			'contain'=>array(
				'Show'=>array(
					'conditions'=>am(
						$this->__showConditions,
						array('Movie.trash'=>0,'Movie.status'=>1)
					),
					'Projection',
					'Movie'=>array(
						'Poster'
					),
					'order'=>'Show.schedule ASC'
				)
			),
		));
		#pr($recordset);
		$billboard = array();
		foreach($recordset as $i => $record){
			$billboard[$i]['Location'] = $record['Location'];
			foreach($record['Show'] as $show){
				$movieId= $show['Movie']['id'];
				$billboard[$i]['Show'][$movieId]['Movie'] = $show['Movie'];
				unset($show['Movie'],$show['Poster']);
				//$recordset[$i]['Show'][$movieId]['Show'][]= am($show['show'],array('Projection'=>$show['Projection']));
				if(empty($show['room_type']) || strpos($show['room_type'],'premier') === false){
					$billboard[$i]['Show'][$movieId]['Normal'][$show['Projection']['lang']."|".$show['Projection']['format']][]= am($show['Projection'],$show);
				}else{
					$billboard[$i]['Show'][$movieId]['Premier'][$show['Projection']['lang']."|".$show['Projection']['format']][]= am($show['Projection'],$show);
				}

			}
		}
		return $billboard;
	}

	function __setCitySelected($data = null){
		if(empty($data)){
			$slug = $this->params['slug'];
			$data['City'] = Configure::read("CitySelected");
			if( $slug != Configure::read("CitySelected.slug") ) {

				$data = $this->Show->Location->City->findBySlug($slug);
			}
		}

		if( ! empty($data) ) {
			$this->Cookie->write("CitySelected", $data['City'], false, mktime(0,0,0,date("m"), date("d"), date("Y") + 1));
			Configure::write("CitySelected", $data['City']);
			$this->set("CitySelected",$data['City']);

			$_locations = $this->Show->Location->find("all",array(
				'conditions'=>array(
					'Location.trash'=>0,
					'Location.status'=>1,
					'Location.city_id'=>$data['City']['id'],
				),
				'fields'=>$this->Show->Location->publicFields
			));
			#pr($_locations);
			$locations = array();
			$locationsList = array();
			foreach($_locations as $record){
				$locations[$record['Location']['id']] = $record;
				$locationsList[$record['Location']['id']] = $record['Location']['name'];
			}

			$this->Cookie->write("LocationsSelected", json_encode($locations), false, mktime(0,0,0,date("m"), date("d"), date("Y") + 1));
			$this->set("LocationsSelected",$locations);
			Configure::write("LocationsSelected",$locations);
			Configure::write("LocationsList",$locationsList);
			Cache::write("LocationsList",$locationsList);
			#pr($this->Cookie->read("LocationsSelected"));

		} else {
			$this->cakeError('error404');
		}


	}

	function get(){
		return $this->Show->find($this->params['type'], $this->params['query']);
	}

	 function admin_sync($location = "all"){
		exec(APP."vendors".DS."cakeshell sync $location -cli /usr/bin -console ".CAKE_CORE_INCLUDE_PATH.DS.CAKE."console -app ".APP." > ".CAKE_CORE_INCLUDE_PATH.DS."sync_manual &");
		Cache::write("sync_billboard_status.running",true);
		if($location == "all"){
			$locations = $this->Show->Location->find("list",array('trash'=>0,'status'=>1));
			foreach($locations as $id => $record){
				Cache::write("sync_billboard_status.locations.$id.running",true);
			}
		}else{
			Cache::write("sync_billboard_status.locations.$location.running",true);
		}
		#pr($this->params);
		$this->redirect($this->referer());
	}

	function admin_syncstatus(){

		#pr("rochin");
		if(!$this->RequestHandler->isAjax()){
			$this->set("locations",$this->Show->Location->find("list",array('trash'=>0,'status'=>1)));
		}else{
			$this->RequestHandler->respondAs('json');
		}
	}

	function buy(){
		$url_error_page = array('controller'=>'pages','action'=>'display','buy_error');
		$this->Show->id = $this->params['show_id'];
		$this->Show->contain(array(
			'TicketPrice',
			'Movie'=>array(
				'Gallery',
				'Poster',
			),
			'Location'=>array(
				'City'
			),
			'Projection'
		));
		$record = $this->Show->read();
		$this->set("record",$record);

		#$dbo = $this->Show->getDatasource();
		#pr(current(end($dbo->_queriesLog)));


		if($record['Show']['seat_alloctype']){
			$this->set("sessionSeatData",$this->__getSeats($record['Location']['vista_service_url'],$record['Show']['session_id']));
		}
		if(!empty($record)){
			if(!empty($this->data)){
				# Se conecta con el servidor Vista
				#TODO: Cachar la ecepcion cuando no se puede conectar al servicio web
				$this->VistaServer = @new SoapClient($record['Location']['vista_service_url'],array('cache_wsdl'=>WSDL_CACHE_NONE));

				if(!$this->Session->check("Tickets")){
					$this->Session->write("Tickets",$this->data);
				}else{
					$this->__TransCancel($this->Session->read("Tickets.Buy.trans_id_temp"));
				}
				# Se obtienen los tipos de boletos seleccionados
				$isTicketsSelected = Set::extract('/BuyTicket[qty>0]',$this->data);
				if(!empty($isTicketsSelected)){
					$this->transIdTemp = $this->__TransNew($record['Location']['vista_service_url']);
					if($this->transIdTemp){

						if($this->__OrderTickets($record['Show'])){

							$dataBuy = $record['Show'];
							$dataBuy['trans_id_temp'] = $this->data['Buy']['trans_id_temp'] = $this->transIdTemp;
							unset($dataBuy['created'],$dataBuy['id']);
							#$dataBuy['BuySeat'] = $this->data['BuySeat'];
							#$dataBuy['BuyTicket'] = $this->data['BuyTicket'];
							if($this->Buy->save($dataBuy,false)){
								$this->data['Buy']['id'] = $this->Buy->id;
							}else{
								$this->redirect($url_error_page);
							}

							if($record['Show']['seat_alloctype']){
								if($this->__isSeatsSelected()){
									if(!$this->__SetSeatsSelected($record['Show'])){
										$this->__TransCancel($this->transIdTemp);
										$this->redirect($url_error_page);
									}
								}else{
									$this->Notifier->error("[:no-se-seleccionaron-asientos:]");
								}

							}

							#$this->__saveBuy();

							$this->Session->write("Tickets",$this->data);
							#$this->Session->write("Tickets.transIdTemp",$this->transIdTemp);
							$paymentTotal = $this->__getPaymentTotal();
							$this->data['Buy']['_ccexp'] = $this->data['Buy']['ccexp']['year']."-".$this->data['Buy']['ccexp']['month']."-1";
							//$this->data['Buy']['_ccexp'] = $ccexp;
							$this->Buy->set($this->data);
							# Se Validan los datos de la tarjeta
							if($this->Buy->validates()){
								$payment = $this->__payment($paymentTotal);
								if($payment){
									list($transaction_id,$confirmation_number) = $payment;

									$this->data['Buy']['trans_number'] = $transaction_id;
									$this->data['Buy']['confirmation_number'] = $confirmation_number;
									$this->data['Buy']['creater'] = $this->loggedUser['User']['id'];
									$this->Buy->save($this->data,false);
									$this->Session->delete("Tickets");
									$this->redirect(array('controller'=>'buys','action'=>'view',$this->Buy->id));
								}else{
									$this->__TransCancel($this->transIdTemp);
									$this->redirect($url_error_page);
								}
							}else{
								$this->Notifier->error("[:informacion-de-pago-incorrecta:]");
							}
						}else{
							$this->__TransCancel($this->transIdTemp);
							#pr("rochin");
							$this->redirect($url_error_page);
						}
					}else{
						$this->redirect(array('controller'=>'pages','action'=>'display','buy_error'));
					}
				}else{
					$this->Notifier->error("[:error-no-tickets-selected:]");
				}
			}else{
				if($this->Session->check("Tickets")){
					$this->VistaServer = @new SoapClient($record['Location']['vista_service_url'],array('cache_wsdl'=>WSDL_CACHE_NONE));
					$this->__TransCancel($this->Session->read("Tickets.Buy.trans_id_temp"));
				}
			}
		}
	}

	function __isSeatsSelected(){
		if(!empty($this->data['BuySeat'])){
			foreach($this->data['BuySeat'] as $seats){
				foreach($seats['grid'] as $seat){
					if($seat != "0"){
						return true;
					}
				}
			}
		}
		return false;
	}


	function __OrderTickets($show){

		#OrderTickets
		$params = array(
			'ClientID'=>env("SERVER_ADDR"),'TransIdTemp'=>$this->transIdTemp,
			'CmdName'=>'OrderTickets',
			'Param1'=>$show['session_id'],#SessionID
			'Param2'=>substr(preg_replace('/-|:|\s/','',$show['schedule']),0,-2),#SessionDateTime
			'Param3'=>$this->__getTicketsDetailsList(),#TicketsDetailsList
			'Param4'=>$show['seat_alloctype'] ? "Y" : "N",#UserSelectedSeating
			'Param5'=>"",#TicketPackageDefinition
			'Param6'=>"" #TicketDetailsListFormat
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));
		#pr($params);
		#pr($response);
		if($response->ExecuteResult == 0){
			#$result = explode("|",$response->ReturnData);
			return true;
		}

		return false;

	}

	function __payment($paymentTotal){

		#PaymentStarting
		$params = array(
			'ClientID'=>env('SERVER_ADDR'),'TransIdTemp'=>$this->transIdTemp,
			'CmdName'=>'PaymentStarting',
			'Param1'=>$paymentTotal,
			'Param2'=>"",
			'Param3'=>"",
			'Param4'=>"",
			'Param5'=>"",
			'Param6'=>""
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));
		#pr($params);
		#pr($response);

		#PaymentOk
		$params = array(
			'ClientID'=>env('SERVER_ADDR'),'TransIdTemp'=>$this->transIdTemp,
			'CmdName'=>'PaymentOk',
			'Param1'=>$this->data['Buy']['cctype'],
			'Param2'=>$this->data['Buy']['ccnumber'],
			'Param3'=>$this->data['Buy']['ccexp']['year'].$this->data['Buy']['ccexp']['month'],
			'Param4'=>$this->data['Buy']['ccname'],
			'Param5'=>"N",
			'Param6'=>""
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));
		#pr($params);
		#pr($response);

		#GetTransactionRefEx
		$params = array(
			'ClientID'=>env('SERVER_ADDR'),'TransIdTemp'=>$this->transIdTemp,
			'CmdName'=>'GetTransactionRefEx',
			'Param1'=>"N",
			'Param2'=>"",
			'Param3'=>"",
			'Param4'=>"",
			'Param5'=>"",
			'Param6'=>""
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));
		#pr($params);
		#pr($response);


		if(!$response->ExecuteResult){
			$result = explode("|",$response->ReturnData);
			if(isset($result[8]) && isset($result[9])){
				$transaction_number= $result[8];
				$confirmation_number = $result[9];
			}
		}

		#TransComplete
		$params = array(
			'ClientID'=>env('SERVER_ADDR'),'TransIdTemp'=>$this->transIdTemp,
			'CmdName'=>'TransComplete',
			'Param1'=>"0",
			'Param2'=>"",
			'Param3'=>"",
			'Param4'=>"",
			'Param5'=>"",
			'Param6'=>""
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));
		#pr($params);
		#pr($response);
		if(!$response->ExecuteResult){
			if(isset($transaction_number) && isset($confirmation_number)){
				return array($transaction_number,$confirmation_number);
			}
		}
		return false;
		/**/
	}

	function __getPaymentTotal(){
		#GetPaymentTotal
		$params = array(
			'ClientID'=>env('SERVER_ADDR'),'TransIdTemp'=>$this->transIdTemp,
			'CmdName'=>'GetPaymentTotal',
			'Param1'=>"",
			'Param2'=>"",
			'Param3'=>"Y",
			'Param4'=>"",
			'Param5'=>"",
			'Param6'=>""
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));
		#pr($params);
		#pr($response);

		if($response->ExecuteResult == 0){
			$result = explode("|",$response->ReturnData);
			return $result[6];
		}

		return false;
	}

	function __SetSeatsSelected($show){
		$params = array(
			'ClientID'=>env('SERVER_ADDR'),'TransIdTemp'=>$this->transIdTemp,
			'CmdName'=>'SetSelectedSeatsEx',
			'Param1'=>$show['session_id'],#SessionID
			'Param2'=>$this->__selectedSeatString(),#SessionDateTime
			'Param3'=>"",
			'Param4'=>"",
			'Param5'=>"",
			'Param6'=>""
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));
		#pr($params);
		#pr($response);
		return !$response->ExecuteResult;
	}

	function __TransCancel($transId){
		$params = array(
			'ClientID'=>env("SERVER_ADDR"),'TransIdTemp'=>$transId,#"20000000499",#$transId,
			'CmdName'=>'TransCancel',
			'Param1'=>"",#$record['Show']['session_id'],#SessionID
			'Param2'=>"",
			'Param3'=>"",
			'Param4'=>"",
			'Param5'=>"",
			'Param6'=>"",
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));

		$this->Session->delete("Tickets");
		#pr($params);
		#pr($response);
		if(!$response->ExecuteResult){
			$this->Buy->deleteAll(array('Buy.trans_id_temp'=>$transId));
		}

	}

	function __TransNew(){
		/**/
		$params = array(
			'ClientID'=>env("SERVER_ADDR"),'TransIdTemp'=>"",
			'CmdName'=>'Register',
			'Param1'=>"WebServer",
			'Param2'=>"WebSite",
			'Param3'=>"0000",
			'Param4'=>"MULTITIMEZONE",#"201505111701",#date("YmdHi"),
			'Param5'=>"NONE",
			'Param6'=>"WWW"
		);

		$response = $this->VistaServer->__soapCall("Execute",array($params));

		#pr($params);
		#pr($response);
		/**/
		$params = array(
			'ClientID'=>env('SERVER_ADDR'),#"".rand(0,10000),
			'TransIdTemp'=>"",#.rand(0,10000000),
			'CmdName'=>'TransNew',
			'Param1'=>"",
			'Param2'=>"",
			'Param3'=>"",'Param4'=>"",'Param5'=>"",'Param6'=>""
		);

		$response = $this->VistaServer->__soapCall("Execute",array($params));
		if($response->ExecuteResult == 0){
			$result = explode("|",$response->ReturnData);
			$transId = $result[6];
			return $transId;

		}

		return false;
		#pr($params);
		#pr($response);

	}

	function __getTicketsDetailsList(){
		$details = "";
		$numTickets = 0;
		foreach($this->data['BuyTicket'] as $tickets){
			if($tickets['qty'] > 0){
				$details.=$tickets['code']."|".$tickets['qty']."|".$tickets['price']."|";
				$numTickets ++;
			}
		}
		$details = "|$numTickets|$details";
		return $details;
	}

	function __selectedSeatString(){
		$num = 0;
		$r = "";
		foreach($this->data['BuySeat'] as $area){
			foreach($area['grid'] as $seat){
				if($seat != "0"){
					$num++;
					list($row_physical,$row,$column) = split("-",$seat);
					$r .= $area['area_category']."|".$area['area_number']."|".$row."|".$column."|";
				}
			}
		}
		return "|".$num."|".$r;
	}

	function seatlayout($show_id = null){
		$this->Show->id = $show_id;
		$this->Show->contain(array('Location'));
		$record = $this->Show->read();
			$this->set("sessionSeatData",$this->__getSeats($record['Location']['vista_service_url'],$record['Show']['session_id']));
		/*if($this->RequestHandler->isAjax()){

		}*/
	}

	function __getSeats($server,$session_id){
		$this->VistaServer = @new SoapClient($server,array('cache_wsdl'=>WSDL_CACHE_NONE));
		$params = array(
			'ClientID'=>env('SERVER_ADDR'),'TransIdTemp'=>"".rand(0,10000000),
			'CmdName'=>'TransNew',
			'Param1'=>"",
			'Param2'=>"",
			'Param3'=>"",'Param4'=>"",'Param5'=>"",'Param6'=>""
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));
		if($response->ExecuteResult == 0){
			$result = explode("|",$response->ReturnData);
			$transId = $result[6];
			#pr($transId);
			#$this->Session->write("TransID",$transId);
		}
		$params = array(
			'ClientID'=>env('SERVER_ADDR'),'TransIdTemp'=>$transId,
			'CmdName'=>'GetSessionSeatDataEx',
			'Param1'=>$session_id."",
			'Param2'=>"",
			'Param3'=>"Y",
			'Param4'=>"Y",
			'Param5'=>"",
			'Param6'=>"Y"
		);
		$response = $this->VistaServer->__soapCall("Execute",array($params));
		if($response->ExecuteResult == 0){
			#pr($response->ReturnData);
			$rData = explode("|",$response->ReturnData);
			#pr($rData);
			# En la posicion 7 del arreglo empiezan los datos del sistema
			$index = 7;
			$sessionSeatData['physical_screen_left'] = $rData[$index]; $index++;
			$sessionSeatData['physical_screen_width'] = $rData[$index]; $index++;
			$sessionSeatData['screen_boundary_position_left'] = $rData[$index]; $index++;
			$sessionSeatData['screen_boundary_position_top'] = $rData[$index]; $index++;
			$sessionSeatData['screen_boundary_position_right'] = $rData[$index]; $index++;
			$sessionSeatData['number_relationships_types'] = $rData[$index]; $index++;

			if($sessionSeatData['number_relationships_types']){
				foreach(range(1,$sessionSeatData['number_relationships_types']) as $i){
					$sessionSeatData['relationships_types'][$rData[$index]] = $rData[$index+1];
					$index+=2;
				}
			}
			$total_areas = $sessionSeatData['total_areas'] = $rData[$index];
			foreach(range(1,$total_areas) as $i){
				$index++;
				$area_number = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['area_number'] = $area_number;
				$sessionSeatData['areas'][$area_number]['area_category'] = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['area_layout_top'] = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['area_layout_left'] = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['area_layout_width'] = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['area_layout_height'] = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['area_layout_rows'] = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['area_layout_colums'] = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['is_selectable'] = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['area_description'] = $rData[$index]; $index++;
				$sessionSeatData['areas'][$area_number]['area_description_alt'] = $rData[$index]; $index++;
				$total_rows = $sessionSeatData['areas'][$area_number]['total_rows'] = $rData[$index]; $index++;
				#pr($sessionSeatData);
				for($i = 1; $i<=$total_rows; $i++){
					#pr($index);
					if(!isset($rData[$index])){
						$index++;
					}
					#pr($index);
					if(!preg_match('/([0-9A-Fa-f]{2})([\s\d]){5}(\d)(\d)/', $rData[$index+2])){

						/*
							Se verifica que 2 posiciones mas adelante del arreglo sea un asiento
							esto por que en ocaciones viene un numero de asiento fisico con un | (pipe),
							se pone el elemento en la cadena de asientos de la fila anterior, se elimina el elemento
							y se vuelve a ejecutar la fila anterior
						*/
						#pr($rData[$index+2]);
						$rData[$index-1] .= " ".$rData[$index];
						unset($rData[$index]);
						$index -= 3;
						$i--;

					}
					$_row = $rData[$index];
					$sessionSeatData['areas'][$area_number]['rows'][$_row]['seat_grid_row_id'] = $rData[$index]; $index++;
					$sessionSeatData['areas'][$area_number]['rows'][$_row]['row_physical_id'] = $rData[$index]; $index++;

					preg_match_all('/(?<grid_seat_number>[0-9A-F]{2})(?<seat_number>[\s\d]{5})(?<seat_status>\d)(?<priority>\d)/',$rData[$index],$matches);
					$index++;
					foreach($matches as $field => $match){
						if(!is_numeric($field)){
							foreach($match as $k => $value){
								$sessionSeatData['areas'][$area_number]['rows'][$_row]['seats'][$matches['grid_seat_number'][$k]][$field] = trim($value);
							}
						}
					}
				}
				#$index++;

				$total_relationships_groups = $sessionSeatData['areas'][$area_number]['total_relationship_groups'] = $rData[$index]; $index++;
				#pr($index);
				foreach(range(1,$total_relationships_groups) as $i){
					//$_row = $rData[$index];
					preg_match_all('/(?<seat_grid_row_id>[0-9A-F]{2})(?<seat_grid_number>[0-9A-F]{2})(?<rel_type_id>[a-zA-Z0-9])/',$rData[$index],$matches);
					$index++;

					foreach($matches as $field => $match){
						if(!is_numeric($field)){
							foreach($match as $k => $value){
								#$sessionSeatData['areas'][$area_number]['relationship_groups'][$i][$k][$field] = trim($value);
								$_row = base_convert($matches['seat_grid_row_id'][$k],16,10);
								//pr("areas"$row." - ")
								$sessionSeatData['areas'][$area_number]['rows'][$_row]['seats'][$matches['seat_grid_number'][$k]]['rel_type_id'] = trim($value);
							}
						}
					}
				}

			}

			#pr($sessionSeatData);
			#pr($rData);
		}else{
			pr("error");
			pr($response);
		}

		return $sessionSeatData;
	}

	function get_movie_schedule($movie_id = null){
		if(empty($movie_id) && isset($this->params['movie_id'])){
			$movie_id = $this->params['movie_id'];
		}else{
			return  false;
		}

		$this->__showConditions = array(
			'Show.schedule >='=>date("Y-m-d H:i:s"),
			'Show.schedule <='=>date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y"))),
			'Show.movie_id'=>$movie_id
		);

		return $this->__getBillboardSchedules();
	}

	function rest($locations = null){
		$conditions = $movieLocationConditions = array();
		if(isset($this->params['named']['locations'])){
			$conditions = array('Show.location_id'=>explode("-",$this->params['named']['locations']));
		}else if(isset($this->params['named']['city'])){
			$locations = $this->Show->Location->find("list",array(
				'conditions'=>array(
					'Location.trash'=>0,
					'Location.status'=>1,
					'Location.id'=>$this->params['named']['city'])
				)
			);
			if(!empty($locations)){
				$conditions = array('Show.location_id'=>array_keys($locations));
				$movieLocationConditions =array('MovieLocation.location_id'=>array_keys($locations));
			}
		}
		if(isset($this->params['named']['schedule']) && $this->params['named']['schedule']){
			if(isset($this->params['named']['city']) || isset($this->params['named']['locations'])){
				if(isset($this->params['named']['city'])){
					Configure::write("CitySelected.id",$this->params['named']['city']);
				}
				if(isset($this->params['named']['locations'])){
					Configure::write("CitySelected.id",$this->params['named']['city']);
				}
				$date = date("Y-m-d");
				$start = strtotime("-30 min");
				if(isset($this->params['named']['date'])){
					$date = $this->params['named']['date'];
					$start = $date." ".date("H:i:s");
					if($date != date("Y-m-d")){
						$start = $date." 0:0:0";
					}
				}
				$end = $date." 23:59:59";
				$this->__showConditions = array(
					'Show.schedule >='=>$start,
					'Show.schedule <='=>$end,
					#'Show.location_id'=> array_keys(Configure::read("LocationsSelected")),
				);
				$this->set("billboard",$this->__getBillboardSchedules());
			}else{
				$this->set("billboard","City or Locations not found");
			}

		}else{
			$query = array(
				'fields'=>array('Show.id'),
				'contain'=>array(
					'Movie'=>array(
						'fields'=>array('Movie.id','Movie.title', 'Movie.genre', 'Movie.duration','Movie.synopsis','Movie.slug'),
						'Poster',
						'MovieLocation'=>array(
							'conditions'=>$movieLocationConditions,
							'limit'=>1,
							'fields'=>array(
								'MovieLocation.presale',
								'MovieLocation.presale_start',
								'MovieLocation.presale_end',
								'MovieLocation.comming_soon',
								'MovieLocation.premiere_end'
							)
						)
					)
				),
				'group'=>array(
					'movie_id'
				),
				'conditions'=>am(array('Movie.trash'=>0,'Movie.status'=>1),$conditions)
			);
			$billboard = $this->Show->find("all",$query);
			foreach($billboard as $key => $item){
				#unset($item['Movie']['MovieLocation'][0]['movie_id'],$item['Movie']['MovieLocation'][0]['id'],[''])
				if(isset($item['Movie']['MovieLocation'][0])){
					$billboard[$key]['Movie'] = am($item['Movie'],$item['Movie']['MovieLocation'][0]);
					unset($billboard[$key]['Movie']['MovieLocation']);
				}

			}
			if(isset($this->params['requested'])){
				return $billboard;
			}
			$this->set("billboard",$billboard);
		}

	}

	function get_date($movie_id = null){
		$conditions = array();
		$dates = $this->Show->find("list",array(
			'fields'=>array('Show.date'),
			'group'=>'Show.date',
			'order'=>'Show.schedule ASC',
			'conditions'=>array(
				'Show.location_id'=> array_keys(Configure::read("LocationsSelected")),
			)
		));
		if(isset($this->params['requested'])){
			return $dates;
		}

	}

	function set_filter(){
		if(!empty($this->data)){
			$data = $this->Session->read("BillboardFilter");
			$this->Session->write("BillboardFilter",$this->data['Filter']);

			if(!empty($this->data['Filter']['location'])){
				foreach($this->data['Filter']['location'] as $location_id){
					$locationsSeleted = Configure::read("LocationsSelected");
					if(!isset($locationsSeleted)){

					}
				}
			}

		}
		$this->redirect($this->referer());
	}

}
?>