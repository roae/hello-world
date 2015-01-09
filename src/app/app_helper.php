<?php
class AppHelper extends Helper{

	function url($url = null, $full = false) {
		if(is_string($url)){
			return Router::url($url, $full);
		}
		$url=Router::url($url, $full);
		if(!preg_match('/#.+/', $url)){
			return preg_replace('/\/$/','',Router::url($url, $full)).'/';
		}
		return preg_replace('/#/','/#',$url);
	}

}
?>