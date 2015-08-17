<?php /* @var $this View */?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td >
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td bgcolor="#00cc67" style="padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:10px;">
						<?= $this->Html->image("check.png",array('class'=>'img_fix'));?>
					</td>
					<td bgcolor="#00cc67" align="center" valign="middle" style="padding:5px 20px 5px 0px;color:#fff;font-size:24px;line-height: 1em;">
						Confirmaci&oacute;n de la compra y reservaci&oacute;n de boletos
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td style="padding-left: 10px;padding-right: 10px;padding-top: 30px; font-size:14px;" bgcolor="#ffffff">
						<span style="color:#cc0017;font-size:24px;">Datos de la orden</span>
						<p style="color:#9b9b9b;margin-bottom: 20px;font-size:12px;">Estimado cliente, gracias por usar el servicio en linea de Citicinemas®. Los datos de su compra est&aacute;n indicados a continuación. Que disfrute su funci&oacute;n.</p>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="color:#464646;">
							<tr>
								<td width="100px">
									<?= $this->Html->image( $this->Uploader->generatePath($record['Poster'],'medium'), array( 'alt' => '[:logo_alt:]','style'=>'width:85px;' ) ) ?>
								</td>
								<td valign="top">
									<table width="100%" cellpadding="0" cellspacing="1" border="0" style="line-height: 1.4em;">
										<tr>
											<td colspan="2" style="font-size: 26px;line-height: 1em;padding-bottom: 5px;"><?= h($record['Movie']['title'])?></td>
										</tr>
										<tr>
											<td width="100px;">[:clasification:]</td>
											<td><?= $record['Movie']['clasification'] ?></td>
										</tr>
										<tr>
											<td>[:ciudad:]</td>
											<td><?= $record['City']['name'] ?></td>
										</tr>
										<tr>
											<td>[:cine:]</td>
											<td><?= $record['Location']['name'] ?></td>
										</tr>
										<tr>
											<td>[:projection_version:]</td>
											<td valign="top">
												<?= $record['Projection']['format']." / ".$record['Projection']['lang'] ?>
												<?php
												if(strpos($record['Buy']['room_type'],"mega") !== false){
													echo $this->Html->tag("span","[:mega-pantalla:]",array('style'=>"background:#F18932; font-size: 10px; color:#fff;padding-top:3px; padding-bottom:2px;padding-left:5px;padding-right:5px;line-height: 1em;"));
												}
												?>
											</td>
										</tr>
									</table>

								</td>
							</tr>
						</table>
						<table width="100%">
							<tr>
								<td></td>
								<td style="width: 300px;">
									<div style="background: #00cc67; padding: 10px; margin-top: 20px; color:#fff;border-radius:4px;">
										<span style="color: #042;font-size:16px;display:block;text-align:center;margin-bottom: 10px;"><?= $this->Time->format("[:l:] d \d\e [:F:]",$record['Buy']['schedule']); ?></span>
										<span style="font-size:30px;line-height: 30px;display:block;text-align:center;margin-bottom: 10px;"><?= $this->Time->format("h:i a",$record['Buy']['schedule']); ?></span>
										<span style="color: #042;font-size:16px;display:block;text-align:center;">
											<?php
											echo $record['Buy']['screen_name'];

											if(strpos($record['Buy']['room_type'],"premier") !== false){
												echo $this->Html->tag("span","[:sala-prermier:]",array('style'=>'background:#E6C845;color:#000; font-size: 10px; padding-top:3px; padding-bottom:2px;padding-left:5px;padding-right:5px;'));
											}
											?>
										</span>
									</div>
								</td>
								<td></td>
							</tr>
						</table>
						<table width="100%" cellpadding="0" cellspacing="1" border="0" style="line-height: 1.4em;color:#464646;padding-top:30px; ">
							<tr>
								<td>
									<table width="100%" cellpadding="0" cellspacing="1" border="0" style="line-height: 1.4em;">
										<tr>
											<td>[:buy-trans-number:]:</td>
											<td width="200px"><?= $record['Buy']['trans_number'] ?></td>
										</tr>
										<tr>
											<td>[:buy-confirmation-number:]</td>
											<td><?= $record['Buy']['confirmation_number'] ?></td>
										</tr>
									</table>
								</td>
								<td align="right">
									<?= $this->Html->image($this->Html->url(array('action'=>'barcode',$record['Buy']['confirmation_number'])));?>
								</td>
							</tr>
						</table>
						<table width="100%" cellpadding="0" cellspacing="1" border="0" style="line-height: 1.4em;color:#464646;padding-top:30px; ">
							<thead>
								<tr>
									<th align="left" style="border-bottom: 1px solid #f1f1f1;">[:tikects:]</th>
									<th align="left" style="border-bottom: 1px solid #f1f1f1;">[:asientos:]</th>
									<th style="border-bottom: 1px solid #f1f1f1;">&nbsp;</th>
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
									<td><?= sprintf("%s %s (%s)",$ticket['qty'],$ticket['description'], $this->Number->currency($ticket['price']/100)); ?></td>
									<td style="color:#00cc67;font-weight: bold;"><?= implode(",",$seats); ?></td>
									<td class="subtotal" style="text-align: center;"><?= $this->Number->currency($price) ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="2" align="right" style="border-top: 1px solid #f1f1f1;">[:total-pagado:]</th>
									<th class="total" style="border-top: 1px solid #f1f1f1;">
										<span style="display:block;font-size:26px; color:#00cc67; font-weight: bold;line-height:1.2em;"><?=  $this->Number->currency($total) ?></span>
										<span style="font-size:12px;">[:incluye-iva:]</span>
									</th>
								</tr>
							</tfoot>
						</table>
						<?php
						if(!$record['Buy']['payment_method']){
							?>
							<div class="paymentInfo" style="color:#464646;padding: 10px 0 0;border-bottom:1px solid #f1f1f1;">
								<span style="color:#cc0017;font-size:16px;padding:10px 0;display:block;border-bottom:1px solid #f1f1f1;">Pago con tarjeta de Cr&eacute;dito</span>
								<div class="info" style="padding-bottom:5px;padding-top:5px;">
									<strong>[:buy-ccending:]:</strong>
									<span class="value" style="padding-left:10px;">**** **** **** <?= $record['Buy']['ccending'] ?></span>
								</div>
								<div class="info" style="padding-bottom:5px;">
									<strong>[:buy-cctype:]:</strong>
									<span class="value" style="padding-left:10px;"><?= $record['Buy']['cctype'] ?></span>
								</div>
								<div class="info" style="padding-bottom:5px;">
									<strong>[:buy-aut_code:]:</strong>
									<span class="value" style="padding-left:10px;"><?= $record['Buy']['aut_code'] ?></span>
								</div>
								<div class="info" style="padding-bottom:5px;">
									<strong>[:buy-created:]:</strong>
									<span class="value" style="padding-left:10px;"><?= $this->Time->format("[:l:] d \d\e [:F:] h:i a",$record['Buy']['created']) ?></span>
								</div>
							</div>
						<?
						}
						?>
						<p style="color:#cc0017;font-size:12px;text-align:center;">Esta compra es INTRASFERIBLE y es propiedad del titular de la tarjeta</p>
					</td>
				</tr>
				<tr>
					<td style="color:#616161; font-size:10px !important;">
						<p>- Sólo el titular de la tarjeta de crédito puede recoger los boletos, llevando consigo la tarjeta de crédito, identificación oficial y firmando el voucher correspondiente en compras mayores a $250.00 pesos.</p>
						<p>- Boletos comprados: son todos aquellos boletos que se compran en línea con tarjeta de crédito (VISA, Mastercard), desde el momento que compra sus boletos, sus lugares están asegurados y puede recogerlos hasta 10 minutos antes de iniciar la función.</p>
						<p>- Sus boletos los pagó en línea al momento de realizar su compra y solamente le recomendamos formarse en la FILA ESPECIAL al accesar a la sala.</p>
						<p>- Le recordamos llevar una identificación y su tarjeta de crédito para firmar el voucher correspondiente.</p>
						<p>- No existen cambios de funciones o número de personas sobre los boletos en linea, por lo que es muy importante hacer la compra o reservación con el número de personas exacto que asistirá a la función. Si tiene algún problema con su función comuníquese al 12313112212</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>