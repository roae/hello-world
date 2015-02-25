<?php
class ShowsController extends AppController{
	var $name = "Shows";
	var $uses = array(
		"Show",
	);

	function index() {
		$slug = $this->params['slug'];
		$data = $this->Show->Location->City->findBySlug($slug);

		if( ! empty($data) ) {
			$this->Cookie->write("City", $data['City'], false, mktime(0,0,0,date("m"), date("d"), date("Y") + 1));

			Configure::write("CitySelected", $data['City']);
		} else {
			$this->cakeError('error404');
		}

		if( Configure::read("LocationSelected") ) {
			$locations = array(
				Configure::read("LocationSelected.id")
			);
		} else {
			$locations = $this->Show->Location->find("list");
			$locations = array_keys($locations);
		}

		$shows = $this->Show->find("all", array(
			'conditions'=>array(
				'Show.schedule >='=>date("Y-m-d H:i:s"),
				'Show.schedule <='=>date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y"))),
				'Show.location_id'=> $locations,
			),
			'contain'=>array(
				'Projection',
				'Movie'=>array(
					'Poster'
				)
			)
		));
		//pr($shows);
		$billboard = array();
		foreach($shows as $show){
			$movieId= $show['Movie']['id'];
			$billboard[$movieId]['Movie'] = $show['Movie'];
			#$billboard[$movieId]['Show'][]= am($show['show'],array('Projection'=>$show['Projection']));
			$billboard[$movieId]['Show'][$show['Projection']['lang']."_".$show['Projection']['format']][]= am($show['Show'],$show['Projection']);
		}
		$this->set("billboard",$billboard);
		#pr(Configure::read("LocationSelected"));

		//$this->set("sessionSeatData",$this->__getSeats());

		//pr($billboard);
	}

	function get(){
		return $this->Show->find($this->params['type'], $this->params['query']);
	}

	function admin_sync($location = "all"){
		exec(APP."vendors".DS."cakeshell sync $location -cli /usr/bin -console ".CAKE_CORE_INCLUDE_PATH.DS.CAKE."console -app ".APP." >> ".CAKE_CORE_INCLUDE_PATH.DS."sync_manual &");
		Cache::write("sync_billboard_status.running",true);
		if($location == "all"){
			$locations = $this->Show->Location->find("list",array('trash'=>0,'status'=>1));
			foreach($locations as $id => $record){
				Cache::write("sync_billboard_status.locations.$id.running",true);
			}
		}else{
			Cache::write("sync_billboard_status.locations.$location_id.running",true);
		}
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
		$this->set("sessionSeatData",$this->__getSeats($this->params['session_id']));
	}

	function __getSeats($session_id){
		$VistaServer = @new SoapClient("http://ciis12.ddns.net/WSVistaSalesSrvr/WSVistaSalesSrvr.asmx?WSDL",array('cache_wsdl'=>WSDL_CACHE_NONE));
		$params = array(
			'ClientID'=>'WEB','TransIdTemp'=>"".rand(0,10000000),
			'CmdName'=>'TransNew',
			'Param1'=>"",
			'Param2'=>"",
			'Param3'=>"",'Param4'=>"",'Param5'=>"",'Param6'=>""
		);
		$response = $VistaServer->__soapCall("Execute",array($params));
		if($response->ExecuteResult == 0){
			$result = explode("|",$response->ReturnData);
			$transId = $result[6];
			#pr($transId);
		}
		$params = array(
			'ClientID'=>'WEB','TransIdTemp'=>$transId,
			'CmdName'=>'GetSessionSeatDataEx',
			'Param1'=>$session_id."",
			'Param2'=>"",
			'Param3'=>"Y",
			'Param4'=>"Y",
			'Param5'=>"",
			'Param6'=>"Y"
		);
		$response = $VistaServer->__soapCall("Execute",array($params));
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

			foreach(range(1,$sessionSeatData['number_relationships_types']) as $i){
				$sessionSeatData['relationships_types'][$rData[$index]] = $rData[$index+1];
				$index+=2;
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

				for($i = 1; $i<=$total_rows; $i++){
					if(!isset($rData[$index])){
						$index++;
					}
					if(!preg_match('/([0-9A-Fa-f]{2})([\s\d]){5}(\d)(\d)/', $rData[$index+2])){
						/*
							Se verifica que 2 posiciones mas adelante del arreglo sea un asiento
							esto por que en ocaciones viene un numero de asiento fisico con un | (pipe),
							se pone el elemento en la cadena de asientos de la fila anterior, se elimina el elemento
							y se vuelve a ejecutar la fila anterior
						*/

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

}
?>