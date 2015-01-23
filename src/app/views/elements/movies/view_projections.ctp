<?
/* @var $this View */
?>
<table class="grid">
	<thead>
		<tr>
			<th>[:Projection_vista_code:]</th>
			<th>[:Projection_lang:]</th>
			<th>[:Projection_format:]</th>
		</tr>
	</thead>
	<tbody>
		<?
		foreach($projections as $projection):
		?>
		<tr>
			<td><?= $projection['vista_code']?></td>
			<td><?= $projection['lang']?></td>
			<td><?= $projection['format']?></td>
		</tr>
		<?
		endforeach;
		?>
	</tbody>
</table>