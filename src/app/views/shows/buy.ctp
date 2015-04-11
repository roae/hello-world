<?php /* @var $this View  */
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
			<header>
				<h1 class="blured-title">
					<?= Inflector::humanize(low($record['Movie']['title'])) ?>
				</h1>
				<p><?= Inflector::humanize(low($record['Movie']['original_title'])) ?></p>
			</header>
		</div>
	</div>

	<div class="movie-information col-container">
		<div class="details">
			<div class="cover-container">
				<?= $this->Html->image($this->Uploader->generatePath($record['Poster'],'medium'), array('alt'=>'[:logo_alt:]')) ?>
			</div>
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
						echo $this->Form->hidden("$key.code",array('value'=>$ticketPrice['code']));
						echo $this->Form->hidden("$key.qty",array('value'=>0));
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
<?php if(isset($sessionSeatData)){ ?>
	<div class="seatsSelection col-container">
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
	</div>

<?php
}
?>
</div>

