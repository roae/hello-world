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
					<?= $this->Html->image($this->Uploader->generatePath($record['Poster'],'medium'), array('alt'=>'[:logo_alt:]')) ?>
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
		<section class="ticketsSelection col-container">
			<a name="tickets" id="ticketsAnchor"></a>
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
					<tr>
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
		</section>
	<?php
	}
	echo $this->Html->scriptBlock("var BuySeat = ".$this->Javascript->object($this->data['BuySeat']));
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
					<input id="BuyPaymentType0" type="radio" <?= !isset($this->data['Buy']['payment_type']) || (isset($this->data['Buy']['payment_type']) && $this->data['Buy']['payment_type']==0) ? 'checked="checked"': ""?> value="0" name="data[Buy][payment_type]">
					<label for="BuyPaymentType0">
						[:credit-debit-card:]
						<span class="icons">
							<span class="visa-icon"></span>
							<span class="mastercard-icon"></span>
						</span>
					</label>

					<input id="BuyPaymentType1" type="radio" <?= (isset($this->data['Buy']['payment_type']) && $this->data['Buy']['payment_type']==1) ? 'checked="checked"': ""?> value="1" name="data[Buy][payment_type]">
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
	<?php echo $this->Form->end();
}else{
?>
	<div class="col-container">
		<div class="session-buy-error">
			[:session-not-found-message:]
			<?= $this->Html->link("[:back-billboard:]",array( 'controller' => 'shows','action' => 'index','slug' => Inflector::slug( low( $CitySelected['name'] ), '-' )),array('class'=>'btn'));?>
		</div>
	</div>
<?php
} ?>
