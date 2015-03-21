<div class="seatsSelection col-container">
	<div class="stepTitle">
		<strong>[:buy-step-2:]</strong>
		<div class="step-text">
			[:buy-step-2-text:]
		</div>
	</div>
	<div class="room-container">

			<?php
			foreach($sessionSeatData['areas'] as $area){
				?>
				<table class="layout <?= $sessionSeatData['number_relationships_types'] ? "loveSeats":""?>">
					<tr>
						<td class="screen" colspan="<?= $area['area_layout_colums'] + 2?>">[:Pantalla:]</td>

					</tr>
					<?php
					foreach(range($area['area_layout_rows'],1) as $row){
						echo "<tr class='room-layout-row'>";
							echo "<td class='row row-$row'>{$area['rows'][$row]['row_physical_id']}</td>";
							foreach(range($area['area_layout_colums'],1) as $colum){
								$hex = up(str_pad(base_convert($colum, 10, 16),2,"0",STR_PAD_LEFT));
								if(isset($area['rows'][$row]['seats'][$hex])){
									$class = "individual";
									if(isset($area['rows'][$row]['seats'][$hex]['rel_type_id'])){
										$class = $sessionSeatData['relationships_types'][$area['rows'][$row]['seats'][$hex]['rel_type_id']];
									}
									echo $this->Html->tag("td",
											$this->Html->tag("span",$this->Html->image("seat-0.png"),"seat").
											$this->Html->tag("span",$area['rows'][$row]['seats'][$hex]['seat_number'],"number"),
										"place-{$row}-{$colum} status-{$area['rows'][$row]['seats'][$hex]['seat_status']} {$class}"
									);
								}else{
									echo $this->Html->tag("td","<span></span>","place-{$row}-{$colum} empty");
								}

							}
							echo "<td class='row row-$row'>{$area['rows'][$row]['row_physical_id']}</td>";
						echo "</tr>";
					}?>
				</table>
			<?php
			}
			?>

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