
	<?php
	foreach($sessionSeatData['areas'] as $areaKey => $area){
		?>
		<table class="layout <?= $sessionSeatData['number_relationships_types'] ? "loveSeats":""?>">
			<tr>
				<td class="screen" colspan="<?= $area['area_layout_colums'] + 2?>">[:Pantalla:]</td>
			</tr>
			<?php
			$k = 0;
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
						$seat_number = $area['rows'][$row]['seats'][$hex]['seat_number'];
						echo $this->Html->tag("td",
							$this->Html->tag("span",$this->Html->image("seat-0.png"),"seat").
							$this->Html->tag("span",$seat_number,"number"),
							//$this->Form->hidden("BuySeat.$areaKey.area_category",array('value'=>$area['area_category'])).
							//$this->Form->hidden("BuySeat.$areaKey.area_number",array('value'=>$area['area_number'])).
							//$this->Form->checkbox("BuySeat.$areaKey.grid.$k",array('value'=>$area['rows'][$row]['row_physical_id']."-".$row."-".$seat_number."-".$colum,'style'=>'display:none','class'=>'seatCheck')),
							array(
								'class'=>"place-{$row}-{$colum}-{$area['area_category']}-{$area['area_number']} status-{$area['rows'][$row]['seats'][$hex]['seat_status']} {$class}",
								'data-area_category' => $area['area_category'],
								'data-area_number' => $area['area_number'],
								'data-row'=>$row,
								'data-column'=>$colum,
								'data-row_physical'=>$area['rows'][$row]['row_physical_id'],
								'data-column_physical'=>$seat_number,
							)
						);
						$k ++;
					}else{
						echo $this->Html->tag("td","<span></span>","place-{$area['rows'][$row]['row_physical_id']}-{$row}-{$colum} empty");
					}

				}
				echo "<td class='row row-$row'>{$area['rows'][$row]['row_physical_id']}</td>";
				echo "</tr>";
			}?>
		</table>
	<?php
	}
	?>