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
class RemoveTransactionsShell extends Shell{
	/*
	 * SoapClient
	 */
	var $VistaServer;

	/**
	 * Models
	 * @var array
	 */
	var $uses = array(
		'Buy',
		'Location'
	);



	function startUp(){
		$this->Dispatch->clear();
	}

	/**
	 * Metodo que se ejecuta automaticamente desde el comando de consola
	 */
	function main(){
		print_r(date("Y F d H:i:s"));
		#print_r(date("Y F d H:i:s",strtotime("-2 mins")));
		$locations = $this->Location->find("all",array(
			'fields'=>array('Location.vista_service_url','Location.id'),
		));
		#print_r($locations);

		$buysToRemove = array();
		foreach($locations as $record){
			# TODO: poner el tiempo que durara una sesion en la configuracion de la página
			$buys = $this->Buy->find("all",array(
				'fields'=>array('Buy.id','Buy.trans_id_temp'),
				'conditions'=>array(
					'Buy.trans_id_temp <>'=>"-",
					'Buy.confirmation_number'=>"-",
					'Buy.created <'=>date("Y-m-d H:i:s",strtotime("-2 mins")),
					'Buy.location_id'=>$record['Location']['id']
				)
			));
			#$dbo = $this->Location->getDatasource();
			#pr(current(end($dbo->_queriesLog)));
			if(!empty($buys)){
				foreach($buys as $buy){
					$VistaServer = @new SoapClient($record['Location']['vista_service_url'],array('cache_wsdl'=>WSDL_CACHE_NONE));
					$params = array(
						'ClientID'=>'127.0.0.1','TransIdTemp'=>$buy['Buy']['trans_id_temp'],#"20000000499",#$transId,
						'CmdName'=>'TransCancel',
						'Param1'=>"",#$record['Show']['session_id'],#SessionID
						'Param2'=>"",
						'Param3'=>"",
						'Param4'=>"",
						'Param5'=>"",
						'Param6'=>"",
					);
					$response = $VistaServer->__soapCall("Execute",array($params));
					#print_r($params);
					#print_r($response);
					if(!$response->ExecuteResult){
						$buysToRemove[] = $buy['Buy']['id'];
					}
				}
			}
		}
		#print_r($buysToRemove);
		if(!empty($buysToRemove)){
			$this->Buy->deleteAll(array('Buy.id'=>$buysToRemove));
		}
		$this->_stop(1);

	}

}
?>