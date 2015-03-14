<div class="seatsSelection col-container">
	<div class="stepTitle">
		<strong>[:buy-step-2:]</strong>
		<div class="step-text">
			[:buy-step-2-text:]
		</div>
	</div>
	<div class="room-container">
		<div class="layout">
			<div class="screen">[:Pantalla:]</div>
			<?php
			foreach($sessionSeatData['areas'] as $area){
				foreach(range($area['area_layout_rows'],1) as $row){
					echo "<div class='room-layout-row'>";
						echo "<span class='row row-$row'>{$area['rows'][$row]['row_physical_id']}</span>";
						foreach(range($area['area_layout_colums'],1) as $colum){
							$hex = up(str_pad(base_convert($colum, 10, 16),2,"0",STR_PAD_LEFT));
							if(isset($area['rows'][$row]['seats'][$hex])){
								$class = "";
								if(isset($area['rows'][$row]['seats'][$hex]['rel_type_id'])){
									$class = $sessionSeatData['relationships_types'][$area['rows'][$row]['seats'][$hex]['rel_type_id']];
								}
								echo $this->Html->tag("span",
										$this->Html->tag("span",$area['rows'][$row]['seats'][$hex]['seat_number'],"seat"),
									"place-{$row}-{$colum} status-{$area['rows'][$row]['seats'][$hex]['seat_status']} {$class}"
								);
							}else{
								echo $this->Html->tag("span"," ","place-{$row}-{$colum} empty");
							}

						}
						echo "<span class='row row-$row'>{$area['rows'][$row]['row_physical_id']}</span>";
					echo "</div>";
				}
			}
			?>
		</div>
	</div>
</div>