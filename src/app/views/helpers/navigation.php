<?php

class NavigationHelper extends HtmlHelper {
	/**
	 *	Lista de helpers que apoyan a la funcionalidad de este helper.
	 *
	 *	@var array
	 *	@access public
	 */
	var $helpers = array(
		'Html'
	);
	/**
	 *	Es una lista que determina los parametros que se tomaran en cuanda para
	 *	la comparación de URL's.
	 *
	 *	@var array
	 *	@access private
	 */
	var $__isCurrentWhen = array('admin','plugin','controller');

	var $__item=array();

	var $__someCurrentItem = false;
	/**
	 * estructura del ribbonbar
	 * @var array
	 */
	var $ribbonBar=array();

	/**
	 *	Genera los items del menú pasado como parametro en forma de lista HTML
	 *	usando tags ul, li y a.
	 *
	 *	@param array $items Un array que representa una lista de items a
	 *		desplegar en el menú con sus respectivas url.
	 *	@param array $options Una lista de opciones para el tag ul generado.
	 *	@param array $itemOptions Una lista de opciones para los tag li
	 *		generados.
	 *	@access public
	 *	@return string HTML bien formado del menú.
	 */
	function menu($items = array(),$options = array(),$itemOptions = array(),&$__currentChild = false){
		if(empty($items)){
			return null;
		}
		$options = $this->addClass($options,'menu');
		$options = am(array('recursive' => true),$options,array('escape' => false));
		$recursive = $options['recursive'];
		unset($options['recursive']);
		$itemOptions = am(array('isCurrentWhen' => array()),$itemOptions,array('escape' => false));
		$out = '';
		$itemTemplates=array();
		if(isset($items['_templates'])){
			$itemTemplates=$items['_templates'];
			unset($items['_templates']);
		}
		if($count = count($items)){
			$index = 1;
			$section = false;
			while(list($title,$item) = each($items)){
				if($item == '|'){
					$out .= $this->tag('li','&nbsp;',array('class' => 'separator','escape' => false));
					$section = true;
					$index++;
					continue;
				}
				$attributes = $itemOptions;
				$attributes = $index == 1 ? $this->addClass($attributes,'first') : $attributes;
				$attributes = $index == $count ? $this->addClass($attributes,'last') : $attributes;
				if(empty($item) || !is_array($item)){
					$item = array('url' => $item,'isCurrentWhen' => $attributes['isCurrentWhen']);
				}else{
					$item = am(array('url' => null,'isCurrentWhen' => array()),$item);
					$item['isCurrentWhen'] = am($attributes['isCurrentWhen'],$item['isCurrentWhen']);
				}
				$url = $item['url'];
				$isCurrentWhen = $item['isCurrentWhen'];
				unset($item['url'],$item['isCurrentWhen'],$attributes['isCurrentWhen']);
				//$attributes = $this->isCurrent($url,$isCurrentWhen) ? $this->addClass($attributes,'current') : $attributes;
				//pr($url);
				if($this->isCurrent($url,$isCurrentWhen)){
					$attributes = $this->addClass($attributes,'current');
					$__currentChild = true;
				}
				$submenu = '';
				if($recursive && !empty($item['menu'])){
					#Debug::dump($item['menu']);
					$submenu = $this->menu($item['menu'],array(),$itemOptions,$currentChild);
					if($currentChild && in_array('child',$isCurrentWhen)){
						$attributes = $this->addClass($attributes,'current');
					}
					unset($item['menu']);
				}
				if($section || $index == 1){
					$attributes = $this->addClass($attributes,'start-section');
					$section = false;
				}
				if(in_array(current($items),array('|','-')) || $index == $count){
					$attributes = $this->addClass($attributes,'end-section');
				}
				if(!empty($itemTemplates)){
					$item=am($item,$itemTemplates);
				}
				$title=$this->__replaceKeys($title,$item);
				if($link = $this->link($title,$url,$this->__item)){
					if($this->__someCurrentItem){
						$attributes = $this->addClass($attributes,"current");
						$this->__someCurrentItem = false;
					}
					$out .= $this->tag('li',$link . $submenu,$attributes);
				}
				$index++;
			}
			$out = $this->tag('ul',$out,$options);
		}
		return $out;
	}

	function ribbonBar($ribbonBar=array()){
		if(!empty($this->ribbonBar['links'])){
			$ribbonBar['links']=am($ribbonBar['sections'],$this->ribbonBar['links']);
		}
		if(!empty($ribbonBar)){
			$title=$this->tag('h1',$this->__replaceKeys($ribbonBar['title'],$ribbonBar['options']),array('class'=>'ribbonTitle'));
			$separator=$this->tag('div',"&nbsp;",array('class'=>'ribbonSeparator'));
			#$out=$title.=$this->Html->tag("div",);
			$out="";
			foreach($ribbonBar['links'] as $link=>$settings){
				if(is_numeric($link)){
					$out.=$separator;
				}else{
					$text=$this->__replaceKeys($link,$settings['options']);
					$out.=$this->link($text,$settings['url'],$this->__item);
				}
			}
			$out=$this->Html->tag("div",$out,array('class'=>'linksContainer'));
			$container=$this->tag("div",$title.$out,array('class'=>'ribbonContent'));
			$ribbon=$this->tag("div",$container,array('id'=>'ribbon'));
			#echo h($ribbon);
			return $ribbon;
		}
	}

	function addRibbonSection($name,$section){
		$this->ribbonBar['sections'][$name]=$section;
	}

	function __replaceKeys($title,&$item=array()){
		$this->__item=$item;
		return preg_replace_callback('/\{([a-z0-9_\-:]+)\}/',array($this,'__replaceMatches'),$title);
	}

	function __replaceMatches($matches){
		list($match,$key)=$matches;
		if(preg_match('/([A-Za-z0-9_\-]+):([A-Za-z0-9_\-]+)/',$key,$keyMatches)){
			list($match,$key,$type)=$keyMatches;
			if(isset($this->__item[$key])){
				if($type=="img"){
					$options=array();
					if(is_array($this->__item[$key])){
						$src=$this->__item[$key]['src'];
						unset($this->__item[$key]['src']);
						$options=$this->__item[$key];
					}else{
						$src=$this->__item[$key];
					}
					unset($this->__item[$key]);
					return $this->image($src,$options);
				}
			}
		}else if(isset($this->__item[$key])){
			$return=$this->__item[$key];
			unset($this->__item[$key]);
			return $return;
		}
		return $match;
	}

	/**
	 *	Genera un link HTML para un item de menú.
	 *
	 *	@param string $title Texto del link generado.
	 *	@param mixed $url URL que determina un ancla para el link generado.
	 *	@param mixed $options Una lista de opciones para el link generado.
	 *	@access public
	 *	@return string HTML bien formado del link.
	 */
	function link($title,$url = null,$options = array()){
		if(!empty($url) && is_array($url)){
			foreach($url as $key => $value){
				if(is_numeric($key) && preg_match("/^\#{$key}$/",$value)){
					if(isset($this->params['pass'][$key])){
						$url[$key] = $this->params['pass'][$key];
					}else{
						return null;
					}
				}
			}
		}
		$options = am($options,array('escape'=>false));
		return $this->Html->link($title,$url,$options);
	}

	/**
	 *	Determina si la url pasada como argumento es la actual.
	 *
	 *	@param string $url URL a probar.
	 *	@param string $params Parametros que deben considerarse para determinar
	 *		si la URL es la actual.
	 *	@access public
	 *	@return boolean Valor logico que será true en caso de exito, false en
	 *		caso contrario.
	 */
	function isCurrent($url = null,$params = array()){
		if(is_string($url)){
			$url=I18nRouter::interpretUrl($url);
		}
		$current = Router::parse($this->url());
		$urlParsed = Router::parse($this->url($url));

		$params=am($this->__isCurrentWhen,$params);

		if(in_array('url', $params)){
			if(is_array($url)){
				$url=$this->url($url);
			}
			return preg_replace('/\/$/','',$url)==preg_replace('/\/$/','',$this->here);
		}
		foreach($params as $param){
			if(isset($urlParsed[$param],$current[$param])){
				if($urlParsed[$param] != $current[$param]){
					return false;
				}
			}
		}
		/*if(in_array('child',$params)){
			$this->__someCurrentItem = true;
		}*/
		return true;

	}
}
?>