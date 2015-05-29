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
	);

	var $validate = array(
		'ccname'=>array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'ccnumber'=>array(
			'rule' => array('cc', 'all', false, null),'message' => '[:invalid-credit-card-number:]'
		),
		'_ccexp'=>array(
			'fecha' => array( 'rule' => array('date'), 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'cvv'=>array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		)
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
				$this->BuySeat->delete(array('BuyTicket.buy_id'=>$this->id));
				foreach($data['BuySeat'] as $grids){
					foreach($grids['grid'] as $seat){
						if($seat){
							list($row_physical,$row,$column) = explode("-",$seat);
							$seatData = array(
								'buy_id'=>$this->id,
								'column'=>$column,
								'row'=>$row,
								'row_physical'=>$row_physical
							);

							$this->BuySeat->create();
							if(!$this->BuySeat->save($seatData)){
								$buySeatsDone = false;
							}
						}
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