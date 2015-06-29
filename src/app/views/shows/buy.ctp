<?php /* @var $this View  */
if(!empty($record)){
	$this->Html->script("buy.min.js",array('inline'=>false));
	echo $this->Form->create("Buy",array('url'=>$this->Html->url()));
	?>
	<div class="session-buy-container" id="SessionCheckout">
		<?php
		$bg_url = '';
		$class = '';

		if( isset($record['Movie']['Gallery'][0]['url']) ) {
			$bg_url = $record['Movie']['Gallery'][0]['url'];
		} else {
			$class = 'image-not-founded';
		}
		?>

		<div class="big-cover <?= $class ?>">
			<div class="bg" style="background-image: url(<?= $bg_url ?>)"></div>
			<div class="col-container">
				<div class="cover-container">
					<?= $this->Html->image($this->Uploader->generatePath($record['Poster'],'medium'), array('alt'=>$record['Movie']['title'])) ?>
				</div>
				<div class="movie-information">
					<h1 class="blured-title">
						<?= Inflector::humanize(low($record['Movie']['title'])) ?>
					</h1>
					<h2 class="sub-title"><?= Inflector::humanize(low($record['Movie']['original_title'])) ?></h2>
					<div class="movie-details">

						<?php if( $record['Movie']['clasification'] != '' ): ?>
							<div class="info">
								<strong>[:clasification:]:</strong>
								<span class="value"><?= $record['Movie']['clasification'] ?></span>
							</div>
						<?php endif; ?>
						<div class="info">
							<strong>[:ciudad:]</strong>
							<span class="value"><?= $record['City']['name'] ?></span>
						</div>
						<div class="info">
							<strong>[:cine:]</strong>
							<span class="value"><?= $record['Location']['name'] ?></span>
						</div>
						<div class="info">
							<strong>[:projection_version:]</strong>
							<span class="value"><?= $record['Projection']['format']." / ".$record['Projection']['lang'] ?></span>
							<?php
							if(strpos($record['Show']['room_type'],"mega") !== false){
								echo $this->Html->tag("span","[:mega-pantalla:]",'mega');
							}
							?>
						</div>
						<div class="info">
							<strong>[:sala:]</strong>
							<span class="value"><?= $record['Show']['screen_name'] ?></span>
							<?php
							if(strpos($record['Show']['room_type'],"premier") !== false){
								echo $this->Html->tag("span","[:sala-prermier:]",'premier');
							}
							?>
						</div>
						<div class="info">
							<strong>[:fecha:]</strong>
							<span class="value"><?= $this->Time->format("[:l:] d \d\e [:F:]",$record['Show']['schedule']); ?></span>
						</div>
					</div>
					<div class="schedule">
						<div class="title">[:function:]</div>
						<span class="value"><?= $this->Time->format("h:i a",$record['Show']['schedule']); ?></span>
						<?= $this->Html->link("[:cambiar-horario:]",array('controller'=>'shows','action'=>'index','slug'=>$CitySelected['slug'],'#'=>$record['Movie']['slug']),array('class'=>'btn')); ?>
					</div>
				</div>
			</div>
		</div>
		<?= $this->Session->flash(); ?>
		<a name="tickets" id="ticketsAnchor"></a>
		<section class="ticketsSelection col-container">

			<div class="stepTitle">
				<strong>[:buy-step-1:]</strong>
				<div class="step-text">
					[:buy-step-1-text:]
				</div>
			</div>
			<table>
				<thead>
					<tr>
						<th></th>
						<th>Precio</th>
						<th>Cantidad</th>
						<th>Subtotal</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($record['TicketPrice'] as $key => $ticketPrice){
					?>
					<tr data-code="<?= $ticketPrice['code']?>" >
						<th><?= $ticketPrice['description']?></th>
						<td data-price="<?= $ticketPrice['price'] ?>" class="price">$<?= number_format($ticketPrice['price'], 2, ".", ",") ?> c/u</td>
						<td class="buttons">
							<button type="button" class="less">-</button>
							<span class="cantidad" data-qty="0">0</span>
							<button type="button" class="plus">+</button>
							<?php
							echo $this->Form->hidden("BuyTicket.$key.description",array('value'=>$ticketPrice['description']));
							echo $this->Form->hidden("BuyTicket.$key.code",array('value'=>$ticketPrice['code']));
							echo $this->Form->hidden("BuyTicket.$key.qty",array('value'=> isset($this->data['BuyTicket'][$key]['qty'])? $this->data['BuyTicket'][$key]['qty'] : 0 ,'class'=>'qtyInput','rel'=>'qty'));
							echo $this->Form->hidden("BuyTicket.$key.price",array('value'=>$ticketPrice['price']*100));
							?>
						</td>
						<td class="subtotal">
							$0.00
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>

			<div class="total">
				<span class="title">Total</span>
				<span class="value">$0.00</span>
				<span class="taxes">Incluye IVA</span>
			</div>
			<div class="loyaltyCard">
				<div class="input text">
					<label for="">[:loyalty-card-text:]</label>
					<input type="text" id="loyalty"/>
					<button typ="button" id="loyaltyButton" class="btn-primary">[:add-card:]</button>
				</div>
				<div>[:que-es-loyalty:]</div>
			</div>
		</section>
	<?php if($record['Show']['seat_alloctype']){ ?>
		<a name="seats" id="ticketsAnchor"></a>
		<section class="seatsSelection col-container">
			<div class="stepTitle">
				<strong>[:buy-step-2:]</strong>
				<div class="step-text">
					[:buy-step-2-text:]
				</div>
			</div>
			<div class="room-container">

				<div id="SeatLayout" data-show="<?= $record['Show']['id']?>">
					<div class="loadingSeats">
						<i class="icon-loading"></i>
						<div>[:cargando-asientos:]</div>
					</div>
				</div>

				<div class="message">
					<div class="content">
						<p>
							No nos has dicho cuantos boletos quieres :)
						</p>
						<a href="#tickets" class="btn">[:select-tickets:]</a>
					</div>
				</div>

				<div class="instructions">
					<span class="available">
						[:disponibles:]
					</span>
					<span class="unavailable">
						[:ocupados:]
					</span>
					<span class="yourseats">
						[:tus-asientos:]
					</span>
				</div>
			</div>
			<div id="seatsInputs">

			</div>
		</section>
	<?php
	}

	echo $this->Html->scriptBlock("var BuySeat = ".$this->Javascript->object(isset($this->data['BuySeat']) ? $this->data['BuySeat'] : array()));
	?>
		<section class="paymentInfo col-container">
			<div class="stepTitle">
				<strong>[:buy-step-3:]</strong>
				<div class="step-text">
					[:buy-step-3-text:]
				</div>
			</div>

			<div class="paymentForm">
				<fieldset class="paymentTypes">
					<input id="BuyPaymentType0" type="radio" <?= !isset($this->data['Buy']['payment_method']) || (isset($this->data['Buy']['payment_method']) && $this->data['Buy']['payment_method']==0) ? 'checked="checked"': ""?> value="0" name="data[Buy][payment_type]">
					<label for="BuyPaymentType0">
						[:credit-debit-card:]
						<span class="icons">
							<span class="visa-icon"></span>
							<span class="mastercard-icon"></span>
						</span>
					</label>

					<input id="BuyPaymentType1" type="radio" <?= (isset($this->data['Buy']['payment_method']) && $this->data['Buy']['payment_method']==1) ? 'checked="checked"': ""?> value="1" name="data[Buy][payment_method]">
					<label for="BuyPaymentType1">
						[:paypal-account:]
						<span class="icons">
							<span class="paypal-icon"></span>
						</span>
					</label>
				</fieldset>
				<?php
				echo $this->I18n->input("ccnumber",array(
					'placeholder'=>'[:sixteen-digits-card:]',
					'between'=>$this->Html->tag("span","",'card-number-icon'),
					'after'=>$this->Html->tag("span","","ccType")#.$this->Html->tag("span","","ccType MASTERCARD")
				));
				echo $this->Form->hidden("trans_id_temp");
				echo $this->Html->tag("div",$this->I18n->input("cctype",array('options'=>array('VISA'=>"Visa",'MASTERCARD'=>'Master Card'))),array('id'=>'CCType'));

				echo $this->I18n->input("ccname",array('placeholder'=>'[:name-in-card:]'));
				$validationErrors = (isset($this->validationErrors['Buy']['_ccexp']))?  $this->Html->div("error-message",$this->validationErrors['Buy']['_ccexp']): false;
				echo $this->Html->div("input exp-date ".(($validationErrors) ? "error": ""),
					$this->Html->tag("label","[:exp-date:]",array('for'=>'BuyExpMonth')).
					$this->I18n->month("ccexp",null,array('monthNames'=>false,'empty'=>'[:month:]')).
					$this->I18n->year("ccexp",date("Y"),date("Y")+6,null,array('empty'=>'[:year:]','orderYear'=>'asc')).
					$validationErrors

				);
				echo $this->I18n->input("cvv",array('div'=>array('class'=>'input text cvv'),'between'=>$this->Html->tag("span","",'cvv-icon'),'placeholder'=>'[:cvv-text:]'));
				echo "<hr />";
				echo $this->I18n->input("email",array('placeholder'=>'[:your-email:]'));

				?>
				<div class="button">
					<button type="submit" class="btn-primary" >[:completar-compra:]</button>
				</div>

				<div class="disclaimer">
					[:buy-disclaimer:]
				</div>

			</div>

		</section>

	</div>
	<div id="buyResume">
		<div class="col-container">
			<div class="buyData">
				<div class="movie-details">
					<?= $this->Html->image($this->Uploader->generatePath($record['Poster'],'medium'), array('alt'=>$record['Movie']['title'],'class'=>'poster')) ?>
					<div class="title">
						<?= Inflector::humanize(low($record['Movie']['title'])) ?>
					</div>
					<div class="info">
						<strong>[:cine:]</strong>
						<span class="value"><?= $record['Location']['name']." - ".$record['City']['name'] ?></span>
					</div>
					<div class="info">
						<strong>[:fecha:]</strong>
						<span class="value"><?= $this->Time->format("[:l:] d \d\e [:F:]",$record['Show']['schedule']); ?></span>
					</div>
					<div class="info">
						<strong>[:hora:]</strong>
						<span class="value"><?= $this->Time->format("h:i a",$record['Show']['schedule']); ?></span>
					</div>
				</div>
				<div class="tickets-details">
					<table>
						<colgroup>
							<col span="1" />
							<col span="1" width="100px" />
							<col span="1" width="100px" />
						</colgroup>
						<thead>
						<tr>
							<th colspan="2">
								[:boletos:]
								<a href="#tickets" class="btn-success btnSelectTicket">[:select-tickets:]</a>
							</th>
							<th>
								[:asientos:]
								<a href="#seats" class="btn-success btnSelectSeats">[:select-seats:]</a>
							</th>
						</tr>
						</thead>
						<tbody>
							<?php foreach($record['TicketPrice'] as $ticket):?>
							<tr class="ticket" data-code="<?= $ticket['code']?>">
								<td>
									<span class="qty"></span>
									<?= $ticket['description']?>
								</td>
								<td class=subtotal></td>
								<td class="seats"></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="total">
					<div class="titleSeccion">[:total:]</div>
					<span class="value">$0.00</span>
					<span class="iva">[:incluye-iva:]</span>
				</div>
				<? if(isset($remainingTime)){ ?>
				<div class="time">
					[:remaining-time:]
					<span class="value"></span>
				</div>
				<? } ?>
			</div>

		</div>
	</div>
	<?php
	/*if(isset($this->data['buyExpDate'])){
		echo $this->Form->hidden("buyExpDate",array('name'=>'data[buyExpDate]','value'=>$this->data['buyExpDate']));
	}*/
	echo $this->Form->end();
	//if(isset($remainingTime)){
		echo $this->Html->scriptBlock("
		var remainingTime = ".(isset($remainingTime)? $remainingTime : "false").";
		var urlExp = '".$this->Html->url(array('controller'=>'pages','action'=>'display','buy_expired_error'))."';
		var urlError = '".$this->Html->url(array('controller'=>'pages','action'=>'display','buy_error'))."';"
		);
	//}
}else{
?>
	<div class="col-container">
		<div class="session-buy-error">
			[:session-not-found-message:]
			<?= $this->Html->link("[:back-billboard:]",array( 'controller' => 'shows','action' => 'index','slug' => Inflector::slug( low( $CitySelected['name'] ), '-' )),array('class'=>'btn'));?>
		</div>
	</div>
<?php
}
$this->I18n->addMissing("no-tickets-selected-yet","Mensaje que aparece cuando intenta seleccionar asientos sin seleccionar boletos",'modulo',true);
$this->I18n->addMissing("select-tickets","Boton del mensaje que aparece cuando intenta seleccionar asientos sin seleccionar boletos",'modulo',true);
$this->I18n->addMissing("select-seats","Boton seleccionar asientos que aparece en la barra de info",'modulo',true);
$this->I18n->addMissing("session-not-found-message","Mensaje de error que aparece cuando no se encuentra la sesion solicitada",'modulo',true);
$this->I18n->addMissing("back-billboard","Boton que dice volver a la cartelera cuando ocurre un error en la compra",'modulo',true);
$this->I18n->addMissing("error-no-tickets-selected","Mensaje flash que aparece cuando no selecciono boletos",'modulo',true);
$this->I18n->addMissing("no-se-seleccionaron-asientos","Mensaje flash que aparece cuando no selecciono asientos",'modulo',true);
$this->I18n->addMissing("informacion-de-pago-incorrecta","Mensaje flash que aparece cuando no se pusieron los datos de la tarjeta correctamente",'modulo',true);
$this->I18n->addMissing("invalid-credit-card-number","Mensaje de error del inpu no de tarjeta de credito",'modulo',true);


$this->I18n->addMissing("remaining-time","Texto del cronometro",'modulo',true);
?>
