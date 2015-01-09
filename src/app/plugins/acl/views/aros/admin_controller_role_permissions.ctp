<div class="actions">
	<table>
		<tr>
			<?php
			
			$column_count = 1;
			
			$headers = array('Action');
			
			foreach($roles as $role)
			{
				$headers[] = $role[$role_model_name][$role_display_field];
				$column_count++;
			}
			
			echo $html->tableHeaders($headers);
			?>
		</tr>
		
		<?php

		foreach($actions as $ctrl_info){
			echo '<tr>';
			echo '<td>'. $ctrl_info['name'] . '</td>';
			foreach($roles as $role)
			{
				echo '<td>';
				
				if(isset($ctrl_info['permissions'][$role[$role_model_name]['id']])){
					$action=$ctrl_info['permissions'][$role[$role_model_name]['id']] ? "deny_role_permission" : "grant_role_permission";
					$class = $ctrl_info['permissions'][$role[$role_model_name]['id']] ? "grant" : "deny";
					echo $this->Html->link("",
						$this->Html->url('/')."admin/acl/aros/$action/" . $role[$role_model_name]['id']  . "/plugin:".$plugin_name."/controller:" . $controller_name . "/action:" . $ctrl_info['name'],
						array('class'=>'TogglePermision '.$class,'escape'=>false)
					);
				}else{	
					//The right of the action for the role is unknown
					echo $html->image('/acl/img/design/important16.png', array('title' => __d('acl', 'The ACO node is probably missing. Please try to rebuild the ACOs first.', true)));
				}
				
				echo '</td>';
			}
			
			echo '</tr>';
		}
		
		?>
	</table>
</div>