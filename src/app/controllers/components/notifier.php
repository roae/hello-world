<?php
class NotifierComponent extends Object{
	/**
	 *	Nombre del component.
	 *
	 *	@var string
	 *	@access public
	 */
	var $name = 'Notifier';

	/**
	 *	Lista de components que serán utilizados para apoyar el funcionamiento
	 *	de este component.
	 *
	 *	@var array
	 *	@access public
	 */
	var $components = array(
		'Session',
		'RequestHandler',
	);

	/**
	 *	Envía un mensaje de notificación al view.
	 *
	 *	@param string $message Mensaje de notificación que se envía.
	 *	@param string $type Tipo de mensaje (info,warning,success,error).
	 *	@param array $options Lista de opciones HTML
	 *	@access public
	 *	@return void
	 */
	function send($message,$type = null,$options = array()){
		if(is_array($type)){
			$options = $type;
			if(isset($options['type'])){
				$type = $options['type'];
				unset($options['type']);
			}else{
				$type = null;
			}
		}
		if(empty($type)){
			$type = 'info';
		}
		$options['class'] = empty($options['class']) ? $type : ($options['class'] . ' ' . $type);

		if($this->RequestHandler->isAjax()){
			$id = 'flashMessage';
			$options = am($options,compact('message','type','id'));
			$notice = array();
			foreach($options as $key => $value){
				$notice[] = "'$key':'$value'";
			}
			header(sprintf('X-Notifier: {%s}',implode(',',$notice)));
		}else{
			$this->Session->setFlash($message,'notifier',$options);
		}
	}

	/**
	 *	Envía un mensaje de error al view.
	 *
	 *	@param string $message Mensaje de notificación que se envía.
	 *	@param array $options Lista de opciones HTML
	 *	@access public
	 *	@return void
	 */
	function error($message,$options = array()){
		$this->send($message,'error',$options);
	}

	/**
	 *	Envía un mensaje de advertencia al view.
	 *
	 *	@param string $message Mensaje de notificación que se envía.
	 *	@param array $options Lista de opciones HTML
	 *	@access public
	 *	@return void
	 */
	function warning($message,$options = array()){
		$this->send($message,'warning',$options);
	}

	/**
	 *	Envía un mensaje de exito al view.
	 *
	 *	@param string $message Mensaje de notificación que se envía.
	 *	@param array $options Lista de opciones HTML
	 *	@access public
	 *	@return void
	 */
	function success($message,$options = array()){
		$this->send($message,'success',$options);
	}

	/**
	 *	Envía un mensaje de exito al view.
	 *
	 *	@param string $message Mensaje de notificación que se envía.
	 *	@param array $options Lista de opciones HTML
	 *	@access public
	 *	@return void
	 */
	function info($message,$options = array()){
		$this->send($message,'info',$options);
	}
}
?>