<?php
$para = "Corporativo";
if(!empty($datos['Contact']['manager'])){
	$para = $datos['Contact']['manager'];
}
?>
<br />
<br />
<table bgcolor="#ffffff" width="100%">
	<tr>
		<td style="width:30px;"></td>
		<td><p><strong style="display:block;">Nombre: </strong> <?php echo $datos['Contact']['name'];?></p></td>
		<td style="width:30px;"></td>
	</tr>
	<tr>
		<td style="width:30px;"></td>
		<td>
			<p><strong style="display:block;">E-mail: </strong> <?php echo $datos['Contact']['email'];?></p>
			<p><strong style="display:block;">Asunto: </strong> <?php echo $datos['Contact']['subject'];?></p>
			<p><strong style="display:block;">Para: </strong> <?php echo $para;?></p>
		</td>
		<td style="width:30px;"></td>
	</tr>

	<tr>
		<td style="width:30px;"></td>
		<td colspan="2">
			<p><strong>Mensaje: </strong></p>
			<p><?php echo $datos['Contact']['message'];?></p>
			<br />
			<br />
		</td>
		<td style="width:30px;"></td>
	</tr>
	<tr>
		<td style="width:30px;"></td>
		<td colspan="2">
			<p><strong>IP: </strong> <?php echo $datos['Contact']['ip'];?></p>
			<p><?php echo  date("F j, Y, g:i a"); ?></p>
		</td>
		<td style="width:30px;"></td>
	</tr>
</table>