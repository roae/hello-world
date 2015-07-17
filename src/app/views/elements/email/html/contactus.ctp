<br />
<br />
<table>
	<tr>
		<td coldspan="2"><p><strong>Name: </strong> <?php echo $datos['Contact']['name'];?></p></td>
	</tr>
	<tr>
		<td><p><strong>E-mail: </strong> <?php echo $datos['Contact']['email'];?></p></td>
		<td><p><strong>Phone: </strong> <?php echo $datos['Contact']['phone'];?></p></td>
		<td><p><strong>Phone: </strong> <?php echo $datos['Contact']['subject'];?></p></td>
	</tr>
	<tr>
		<td colspan="2">
			<p><strong>Message: </strong></p>
			<p><?php echo $datos['Contact']['message'];?></p>
			<br />
			<br />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p><strong>IP: </strong> <?php echo $datos['Contact']['ip'];?></p>
			<p><strong>URL: </strong> <?php echo $datos['Contact']['url'];?></p>
			<p><?php echo  date("F j, Y, g:i a"); ?></p>
		</td>
	</tr>
</table>