<style type="text/css">
	.seat-layout{
		border: 0;
		margin: 10px auto 100px;
	}
	.seat-layout td{

	}
	.seat-layout .seat{
		margin: 5px 1px;
		display:block;
		width: 50px;
		height: 30px;
		text-align: center;
	}
	.status-0 .seat{
		border:5px solid #f1f1f1;
		border-top:0;
	}
	.status-1 .seat{
		border:4px solid #f00;
		border-top: 0;
	}

	.Multi-Start .seat{
		border-left: 0;
		margin-right: 5px;
	}

	.Multi-End .seat{
		border-right: 0;
		margin-left: 5px;
	}

</style>
<table class="seat-layout">
<?php
foreach($sessionSeatData['areas'] as $area){
	foreach(range($area['area_layout_rows'],1) as $row){
		echo "<tr>";
			echo "<td class='row row-$row'>{$area['rows'][$row]['row_physical_id']}</td>";
			foreach(range($area['area_layout_colums'],1) as $colum){
				$hex = up(str_pad(base_convert($colum, 10, 16),2,"0",STR_PAD_LEFT));
				if(isset($area['rows'][$row]['seats'][$hex])){
					$class = "";
					if(isset($area['rows'][$row]['seats'][$hex]['rel_type_id'])){
						$class = $sessionSeatData['relationships_types'][$area['rows'][$row]['seats'][$hex]['rel_type_id']];
					}
					echo $this->Html->tag("td",
						$this->Html->tag("span",$area['rows'][$row]['seats'][$hex]['seat_number'],"seat"),
						"place-{$row}-{$colum} status-{$area['rows'][$row]['seats'][$hex]['seat_status']} {$class}"
					);
				}else{
					echo $this->Html->tag("td",null,"place-{$row}-{$colum}");
				}

			}
			echo "<td class='row row-$row'>{$area['rows'][$row]['row_physical_id']}</td>";
		echo "</tr>";
	}
}
?>
</table>

<?php
function getClasSeat(){

}
?>