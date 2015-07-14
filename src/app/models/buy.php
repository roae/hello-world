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
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
			# al menos 2 palabras
			'pattern'=>array('rule'=>'/[a-zA-Z]+\s[a-zA-Z]+(\s[a-zA-Z]+)*/','message'   => '[:ccname-invalid:]'),
		),
		'ccnumber'=>array(
			'rule' => array('cc', 'all', false, null),'message' => '[:invalid-credit-card-number:]'
		),
		'_ccexp'=>array(
			'fecha' => array( 'rule' => array('date'), 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'cvv'=>array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
			'numerico'=>array('rule'=>'numeric','required'=>true,'message'=>'[:cvv-solo-numeros:]'),
			'tamaño'=>array('rule' => array('between', 3, 4),'message'=>'[:cvv-de-3-a-4-numeros:]')
		),
		'email'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
			'mail' => array('rule' => 'email','message' => '[:valid_email:]'),
		),
	);

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