<div class="actions">
	<table>
	<tr>
		<?php
		
		$column_count = 1;
		
		$headers = array(__d('acl', 'action', true), __d('acl', 'authorization', true));

		echo $html->tableHeaders($headers);
		?>
	</tr>
	
	<?php
	$previous_ctrl_name = '';
	
	foreach($actions as $_action){
		echo '<tr>';
		
			echo '<td>' . $_action['name'] . '</td>';
			
			echo '<td>';
			if($_action['authorized'] >= 0){
				$action= $_action['authorized'] ? "deny_user_permission" : "grant_user_permission";
				$class= $_action['authorized'] ? "grant" : "deny";
				echo $this->Html->link("",
					$this->Html->url('/')."admin/acl/aros/$action/" . $user[$user_model_name]['id'] . "/plugin:/controller:" . $controller_name . "/action:" . $_action['name'],
					array('class'=>'TogglePermision '.$class,'escape'=>false)
				);
			}else if($_action['authorized'] == -1){
				echo $html->image('/acl/img/design/important16.png');
			}else{
				echo "?";
			}
			echo '</td>';
		echo '</tr>';
	}
	?>
</div>