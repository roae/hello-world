<?php /* @var $this View  */?>
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
				<span class="value"><?= $this->Time->format("h:m a",$record['Show']['schedule']); ?></span>
				<?= $this->Html->link("[:cambiar-horario:]",array('controller'=>'shows','action'=>'index','slug'=>$CitySelected['slug'],'#'=>$record['Movie']['slug']),array('class'=>'btn')); ?>
			</div>
		</div>
	</div>

	<section class="ticketsSelection col-container">
		<div class="title">
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
			foreach($record['TicketPrice'] as $ticketPrice){
				?>
				<tr>
					<th><?= $ticketPrice['description']?></th>
					<td>$<?= number_format($ticketPrice['price'], 2, ".", ",") ?> c/u</td>
					<td class="buttons">
						<button type="button" class="less">-</button>
						<span class="cantidad">0</span>
						<button type="button" class="plus">+</button>
					</td>
					<td>
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
			<span class="value">$134.00</span>
			<span class="taxes">Incluye IVA</span>
		</div>
	</section>


</div>

<? #echo $this->element("shows/seats",array('sessionSeatData'=>$sessionSeatData));?>