<?php

class XhtmlHelper extends HtmlHelper{

	var $values;
	var $helpers=array("Text");

	function para($class, $text, $options = array()) {
		$text=preg_replace('/<\/p>|<br[^<]*?>/',"\n",$text);# se remplazan los <br> y </p> por \n para que los comentarios viejos sean compatibles
		$text=preg_replace("/\\\|<[^<]+?>/","",$text); #se quitan todos las etiquetas html y las \ que se usan para escapar las comillas
		$text=preg_replace("/\n\s*\n/","</p><p>",$text);#se remplazan los dos saltos de lineas por una P
		$text=preg_replace('/\n/',"<br />",$text);#se remplazan los saltos de linea que quedan solos por <br />
		#$comment=preg_replace('/[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-]+\.[a-zA-Z0-9_\-\.]+/','[E-mail]',$text);# se quitan los emails que se escriben en el mensaje
		#$comment=preg_replace('/www\.[a-zA-Z0-9\-_]+\.[a-zA-Z0-9]{2,4}|http:\/\/[a-zA-Z0-9\-_]+\.[a-zA-Z0-9]{2,4}|https:\/\/[a-zA-Z0-9\-_]+\.[a-zA-Z0-9]{2,4}/','[URL]',$comment);
		return parent::para($class,$text,$options);
	}

	function truncatePreserveWords($text,$search,$length=100){
		$pos=stripos($text,$search);
		if($pos!==false){
			if($pos < $length/2){
				$fin=$length-(strlen($search)+$pos);
				$fin = $fin < 0 ? $fin*-1 : $fin;
				preg_match('/^(.{1,'.$pos.'})('.$search.')(.{1,'.$fin.'})\s/is', $text,$match);
				return strlen($match[0]) < strlen($text) ? $match[0]."..." : $match[0];
			}else{
				preg_match('/\s(.{1,'.($length / 2).'})('.$search.')(.{1,'.($length / 2).'})\s/is', $text,$match);
				return "...".$match[0]."...";
			}
		}else{
			return $this->Text->truncate($text,$length,array('ending'=>'...','exact'=>false));
		}
	}

	function highlight($text,$search,$tag="strong",$options=array()){
		$options=array('class'=>'highlight');
		foreach (split(" ",$search) as $word) {
			$text=preg_replace('/('.$word.')/iUs',$this->tag($tag,'$1',$options),$text);
		}
		return $text;
	}

}
?>