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
		'Location',
		'Setting'
	);

	var $config;



	function startUp(){
		$this->Dispatch->clear();
	}

	function initialize(){
		Configure::write('debug', 2);
		$this->_loadDbConfig();
		$this->_loadModels();
	}

	/**
	 * Metodo que se ejecuta automaticamente desde el comando de consola
	 */
	function main(){
		$this->out(date("Y F d H:i:s"));
		$this->config = $this->Setting->getConfig();
		#print_r(date("Y F d H:i:s",strtotime("-2 mins")));
		$locations = $this->Location->find("all",array(
			'fields'=>array('Location.vista_service_url','Location.id'),
		));
		#print_r($locations);

		$buysToRemove = array();
		foreach($locations as $record){
			$buys = $this->Buy->find("all",array(
				'fields'=>array('Buy.id','Buy.trans_id_temp'),
				'conditions'=>array(
					'Buy.trans_id_temp <>'=>"-",
					'Buy.confirmation_number'=>"-",
					'Buy.created <'=>date("Y-m-d H:i:s",strtotime("-{$this->config['buy_remmaining_time']} mins")),
					'Buy.location_id'=>$record['Location']['id']
				)
			));
			//print_r($buys);
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

		$this->dump_sql();
		$this->hr(1);
		$this->_stop(1);

	}

	function dump_sql()
	{
		$sql_dump = '';

		if (!class_exists('ConnectionManager') || Configure::read('debug') < 2)
			return false;

		$noLogs = !isset($logs);
		if ($noLogs)
		{
			$sources = ConnectionManager::sourceList();

			$logs = array();
			foreach ($sources as $source):
				$db =& ConnectionManager::getDataSource($source);
				if (!$db->isInterfaceSupported('getLog')):
					continue;
				endif;
				$logs[$source] = $db->getLog();
			endforeach;
		}

		if ($noLogs || isset($_forced_from_dbo_))
		{
			foreach ($logs as $source => $logInfo)
			{
				$text = $logInfo['count'] > 1 ? 'queries' : 'query';
				$this->out("cakeSqlLog_" . preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time(), true)));
				$this->out('('.$source.') '. $logInfo['count'] .' '.$text. ' took '.$logInfo['time'].' ms');
				$this->out('Nr Query Error Affected Num. rows Took (ms)');

				foreach ($logInfo['log'] as $k => $i)
				{
					$this->out($i['query']);
				}
			}
		}
		else
		{
			$this->out('Encountered unexpected $logs cannot generate SQL log');
		}
		//return $sql_dump;
	}

}
?>