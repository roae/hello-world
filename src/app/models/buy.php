<?php
/**
 * Class Buy
 * @property $BuyTicket BuyTicket
 * @property $BuySeat BuySeat
 */
class Buy extends AppModel{
	var $name = "Buy";
	var $useTable = "buys";

	var $hasMany = array(
		'BuyTicket',
		'BuySeat',
	);

	var $belongsTo = array(
		'Movie',
		'Location',
		'Projection',
		'Buyer'=>array(
			'className'=>'User',
			'foreignKey'=>'buyer',
		)
	);

	var $validate = array(
		'ccname'=>array(
			'pattern'=>array(
				'rule'      => '/^[a-zA-Z]+\s[a-zA-Z]+(\s[a-zA-Z]+)*$/i', # al menos 2 palabras
				'message'   => '[:ccname-invalid:]',
			),
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'ccnumber'=>array(
			'rule' => array('cc', 'all', false, null),'message' => '[:invalid-credit-card-number:]',
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'_ccexp'=>array(
			'expiro'=>array('rule'=>'expiro','message'=>'[:cc-expiro:]'),
			'fecha' => array( 'rule' => array('date'), 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'cvv'=>array(

			'numerico'=>array('rule'=>'numeric','required'=>true,'message'=>'[:cvv-solo-numeros:]'),
			'tamaño'=>array('rule' => array('between', 3, 4),'message'=>'[:cvv-de-3-a-4-numeros:]'),
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'email'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
			'mail' => array('rule' => 'email','message' => '[:valid_email:]'),
		),
	);

	function expiro($data){
		$current_month = mktime(0,0,0,date("m"),1,date("Y"));
		if(preg_match('/(\d{4})\-(\d{1,2})-(\d{1,2})/',$data['_ccexp'])){
			list($year,$month,$day) = explode("-",$data['_ccexp']);
			return mktime(0,0,0,$month,$day,$year) >= $current_month;
		}
		return false;
	}

	function save($data = null, $validate = true, $fieldList = array()){
		$buySeatsDone = true;
		$buyTicketsDone = true;
		$this->begin();
		if(parent::save($data,$validate)){
			if(isset($data['BuyTicket'])){
				$this->BuyTicket->delete(array('BuyTicket.buy_id'=>$this->id));
				foreach($data['BuyTicket'] as $key=>$ticket){
					if($ticket['qty']>0){
						$ticket['buy_id'] = $this->id;
						$this->BuyTicket->create();
						if(!$this->BuyTicket->save($ticket)){
							$buyTicketsDone = false;
						}
					}else{
						unset($data['BuyTicket'][$key]);
					}
				}
			}
			if(isset($data['BuySeat'])){
				$this->BuySeat->delete(array('BuySeat.buy_id'=>$this->id));
				foreach($data['BuySeat'] as $seat){
					$seat['buy_id'] = $this->id;

					$this->BuySeat->create();
					if(!$this->BuySeat->save($seat)){
						$buySeatsDone = false;
					}
				}
			}
			if(!$buySeatsDone || !$buyTicketsDone){
				$this->rollback();
				return false;
			}

		}
		$this->commit();
		return true;


	}

}
?>