<?php
class AppHelper extends Helper{

	function url($url = null, $full = false) {
		$params = Router::getParams();
		if(isset($params['url']['mobile'])){
			$params = "?mobile=".$params['url']['mobile'];
		}else{
			$params = "";
		}
		if(is_string($url)){
			return Router::url($url, $full).$params;
		}
		$url=Router::url($url, $full);

		if(!preg_match('/#.+/', $url)){
			return preg_replace('/\/$/','',Router::url($url, $full)).'/'.$params;
		}
		return preg_replace('/#/','/#',$url);
	}

}
?>