<div class="buy-detail-container">
	<iframe height="0" width="0" name="email" id="print" src="<?= $this->Html->url()?>email:true"></iframe>
	<?php
	$bg_url = '';
	$class = '';

	if( isset( $record['Movie']['Gallery'][0]['url'] ) ){
		$bg_url = $record['Movie']['Gallery'][0]['url'];
	} else{
		$class = 'image-not-founded';
	}
	?>

	<div class="big-cover <?= $class ?>">
		<div class="bg" style="background-image: url(<?= $bg_url ?>)"></div>
		<div class="col-container">
			<?php if($showMessage){ ?>
			<div class="message">
				<span class="icon"></span>
				<span class="principal-text">[:buy-principal-message:]</span>
				<div class="second-text">[:buy-second-messages:]</div>
			</div>
			<?php } ?>
			<div class="show-information">
				<span class="section-title">[:show-details-title:]</span>
				<figure class="poster"><?= $this->Html->image( $this->Uploader->generatePath($record['Poster'],'medium'), array( 'alt' => '[:logo_alt:]' ) ) ?></figure>
				<div class="details">
					<h2 class="movie-title"><?= h($record['Movie']['title'])?></h2>
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
							if(strpos($record['Buy']['room_type'],"mega") !== false){
								echo $this->Html->tag("span","[:mega-pantalla:]",'mega');
							}
							?>
						</div>
						<div class="info">
							<strong>[:sala:]</strong>
							<span class="value"><?= $record['Buy']['screen_name'] ?></span>
							<?php
							if(strpos($record['Buy']['room_type'],"premier") !== false){
								echo $this->Html->tag("span","[:sala-prermier:]",'premier');
							}
							?>
						</div>
						<div class="info">
							<strong>[:fecha:]</strong>
							<span class="value"><?= $this->Time->format("[:l:] d \d\e [:F:]",$record['Buy']['schedule']); ?></span>
						</div>
						<div class="info">
							<strong>[:hora:]</strong>
							<span class="value"><?= $this->Time->format("h:i a",$record['Buy']['schedule']); ?></span>
						</div>
					</div>

				</div>
			</div>
			<div class="buy-information">
				<div class="section-title">[:buy-details-title:]</div>
				<div class="buy-details">
					<div class="info">
						<strong>[:buy-trans-number:]:</strong>
						<span class="value"><?= $record['Buy']['trans_number'] ?></span>
					</div>
					<div class="info">
						<strong>[:buy-confirmation-number:]</strong>
						<span class="value"><?= $record['Buy']['confirmation_number'] ?></span>
					</div>
				</div>
				<figure class="barras">
					<?= $this->Html->image($this->Html->url(array('action'=>'barcode',$record['Buy']['confirmation_number'])));?>
				</figure>
				<table class="tickets">
					<thead>
					<tr>
						<th>[:tikects:]</th>
						<th>[:asientos:]</th>
						<th>&nbsp;</th>
					</tr>
					</thead>
					<tbody>
						<?php
						$index_seat = 0;
						$total =0 ;
						foreach($record['BuyTicket'] as $ticket):
							$seats = array();
							for($i=1; $i<=$ticket['qty']; $i++){
								$seats[]=$record['BuySeat'][$index_seat]['row_physical'].$record['BuySeat'][$index_seat]['column_physical'];
								$index_seat++;
							}
							$price = $ticket['price']/100*$ticket['qty'];
							$total +=$price;
						?>
							<tr>
								<td><?= sprintf("%s %s ($%s)",$ticket['qty'],$ticket['description'],$ticket['price']/100); ?></td>
								<td><?= implode(",",$seats); ?></td>
								<td class="subtotal">$<?= $price ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th>[:total-pagado:]</th>
							<th class="total">
								<span class="value">$<?= $total ?></span>
								[:incluye-iva:]
							</th>
						</tr>
					</tfoot>
				</table>
				<?php
				if(!$record['Buy']['payment_method']){
				?>
					<div class="paymentInfo">
						[:pago-con-tarjeta:]
						<div class="info">
							<strong>[:buy-ccending:]:</strong>
							<span class="value">**** **** **** <?= $record['Buy']['ccending'] ?></span>
						</div>
						<div class="info">
							<strong>[:buy-cctype:]:</strong>
							<span class="value"><?= $record['Buy']['cctype'] ?></span>
						</div>
						<div class="info">
							<strong>[:buy-aut_code:]:</strong>
							<span class="value"><?= $record['Buy']['aut_code'] ?></span>
						</div>
						<div class="info">
							<strong>[:buy-RefSPNum:]:</strong>
							<span class="value"><?= $record['Buy']['refspnum'] ?></span>
						</div>
					</div>
				<?
				}
				?>
			</div>

		</div>
		<div class="buttons">
			<?php
			if($showMessage){
				echo $this->Html->link("[:ir-inicio:]","/",array('class'=>'btn'));
				echo "&nbsp";
				echo $this->Html->link("[:print-ticket:]","#",array('class'=>'btn-primary','onclick'=>'$("#print")[0].contentWindow.print();return false;'));
			}else{
				echo $this->Html->link("[:back-to-profile:]",$referer,array('class'=>'btn'));
				echo "&nbsp";
				echo $this->Html->link("[:print-ticket:]","#",array('class'=>'btn-primary','onclick'=>'$("#print")[0].contentWindow.print();return false;'));
			}
			?>
		</div>
	</div>
</div>
</div>

<?php
$this->I18n->addMissing('buy-principal-message',array('desc'=>'Mensaje que aparece cuando se realiza la compra con exito','tab'=>'modulo'));
$this->I18n->addMissing('buy-second-messages',array('desc'=>'Mensaje que aparece cuando se realiza la compra con exito','tab'=>'modulo'));

$this->Html->script( array(
	'ext/images-loaded.min.js',
	'ext/litebox.min.js',
), false );
?>