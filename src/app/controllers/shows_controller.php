<?php
class ShowsController extends AppController{
	var $name = "Shows";
	var $uses = array("Show");

	function index(){
		$shows=$this->Show->find("all",array(
			'conditions'=>array(
				'Show.schedule >='=>date("Y-m-d H:i:s"),
				'Show.schedule <='=>date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y"))),
				'Show.location_id'=>Configure::read("LocationSelected.id"),
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
			//$this->RequestHandler->renderAs($this, 'json');
		}
	}



}
?>