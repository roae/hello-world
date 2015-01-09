<?php 
echo $this->Html->script('/acl/js/jquery');
echo $this->Html->script('/acl/js/acl_plugin');

echo $this->element('design/header');
?>

<?php 
echo $this->element('aros/links');
?>

<?php 
if(isset($users)){
?>
	<?php 
	echo '<p>&nbsp;</p>';
	echo '<p>';
	echo __d('acl', 'This page allows to manage users specific rights', true);
	echo '</p>';
	echo '<p>&nbsp;</p>';
	?>
	<table border="0" cellpadding="5" cellspacing="2">
	<tr>
		<?php 
		$column_count = 1;
		
		$headers = array($paginator->sort(__d('acl', 'user', true), 'display_name'));
		
		echo $html->tableHeaders($headers);
		?>
	</tr>
	<?php 
	foreach($users as $user)
	{
		echo '<tr>';
		echo '  <td>' . $user[$user_model_name]['display_name'] . '</td>';
		$title = __d('acl', 'Manage user specific rights', true);
		echo '  <td>' . $html->link($html->image('/acl/img/design/lock.png'), '/admin/acl/aros/user_permissions/' . $user[$user_model_name]['id'], array('alt' => $title, 'title' => $title, 'escape' => false)) . '</td>';
		echo '</tr>';
	}
	?>
	<tr>
		<td class="paging" colspan="<?php echo $column_count ?>">
			<?php echo $paginator->numbers(); ?>
		</td>
	</tr>
	</table>
<?php 
}else{
?>
<div class="permissions">
	<h1><?php echo  __d('acl', $user_model_name, true) . ' : ' . $user[$user_model_name]['display_name']; ?></h1>
	
	<h2><?php echo __d('acl', 'Role', true); ?></h2>
	
	<table>
	<tr>
		<?php 
		foreach($roles as $role)
		{
			echo '<td>';
			
			echo $role[$role_model_name][$role_display_field];
			if($role[$role_model_name]['id'] == $user[$user_model_name][$role_fk_name])
			{
				echo $html->image('/acl/img/design/tick.png');
			}
			else
			{
				$title = __d('acl', 'Update the user role', true);
				echo $html->link($html->image('/acl/img/design/tick_disabled.png'), array('plugin' => 'acl', 'controller' => 'aros', 'action' => 'update_user_role', 'user' => $user[$user_model_name]['id'], 'role' => $role[$role_model_name]['id']), array('title' => $title, 'alt' => $title, 'escape' => false));
			}
			
			echo '</td>';
		}
		?>
	</tr>
	</table>
	
	<h2><?php echo __d('acl', 'Permissions', true); ?></h2>
	<?php /*
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
	
	//debug($actions);
	
	foreach($actions['app'] as $controller_name => $ctrl_infos)
	{
		if($previous_ctrl_name != $controller_name)
		{
			$previous_ctrl_name = $controller_name;
			
			$color = (isset($color) && $color == 'color1') ? 'color2' : 'color1';
		}
		
		foreach($ctrl_infos as $ctrl_info)
		{
			//debug($ctrl_info);
			
			echo '<tr class="' . $color . '">';
			
			echo '<td>' . $controller_name . '->' . $ctrl_info['name'] . '</td>';
			
			echo '<td>';
			if($ctrl_info['permissions'][$user[$user_model_name]['id']] >= 0){
				$action= $ctrl_info['permissions'][$user[$user_model_name]['id']] ? "deny_user_permission" : "grant_user_permission";
				$class= $ctrl_info['permissions'][$user[$user_model_name]['id']] ? "deny" : "grant";
				echo $this->Html->link("",
					$this->Html->url('/')."admin/acl/aros/$action/" . $user[$user_model_name]['id'] . "/plugin:/controller:" . $controller_name . "/action:" . $ctrl_info['name'],
					array('class'=>'TogglePermision '.$class,'escape'=>false)
				);
			}else if($ctrl_info['permissions'][$user[$user_model_name]['id']] == -1){
				echo $html->image('/acl/img/design/important16.png');
			}else{
				echo "?";
			}
			echo '</td>';
			echo '</tr>
			';
		}           
	}
	?>
	<?php 
		foreach($actions['plugin'] as $plugin_name => $plugin_ctrler_infos)
		{
			echo '<tr class="title"><td colspan="2">' . __d('acl', 'Plugin', true) . ' ' . $plugin_name . '</td></tr>
			';
			
			foreach($plugin_ctrler_infos as $plugin_ctrler_name => $plugin_methods)
			{
				if($previous_ctrl_name != $plugin_ctrler_name)
				{
					$previous_ctrl_name = $plugin_ctrler_name;
					
					$color = (isset($color) && $color == 'color1') ? 'color2' : 'color1';
				}
				
				foreach($plugin_methods as $method)
				{
					echo '<tr class="' . $color . '">
					';
					
					echo '<td>' . $plugin_ctrler_name . '->' . $method['name'] . '</td>';
					//debug($method['name']);
					
					echo '<td>';

					if($method['permissions'][$user[$user_model_name]['id']] >= 0){
						$action= $method['permissions'][$user[$user_model_name]['id']] ? "deny_user_permission" : "grant_user_permission";
						$class= $method['permissions'][$user[$user_model_name]['id']] ? "deny" : "grant";
						echo $this->Html->link("",
							$this->Html->url('/')."admin/acl/aros/$action/" . $user[$user_model_name]['id'] . "/plugin:" . $plugin_name . "/controller:" . $plugin_ctrler_name . "/action:" . $method['name'],
							array('class'=>'TogglePermision '.$class,'escape'=>false)
						);
					}else if($method['permissions'][$user[$user_model_name]['id']] == -1){
						echo $html->image('/acl/img/design/important16.png');
					}else{
						echo "?";
					}
					echo '</td>';
					echo '</tr>
					';
				}
			}
		}
		?>
	</table>
	<?php 
	echo $html->image('/acl/img/design/tick.png') . ' ' . __d('acl', 'authorized', true);
	echo '&nbsp;&nbsp;&nbsp;';
	echo $html->image('/acl/img/design/cross.png') . ' ' . __d('acl', 'blocked', true);
	*/

	echo $this->Html->tag("ul",null);
	foreach($controllers as $level=>$_controllers){
		ob_start();
		echo $this->Html->tag("ul",null);
			foreach($_controllers as $plugin=>$_controller){
				if(is_string($_controller)){
					echo $this->Html->tag("li",$this->Html->link($_controller,array('action'=>'controller_user_permissions',$user['User']['id'],$_controller),array('class'=>'controller')));
				}else{
					$buffer="";	
					$buffer.=$this->Html->tag("ul",null);
					foreach($_controller as $controller_name){
						$buffer.=$this->Html->tag("li",$this->Html->link($controller_name,array('action'=>'controller_user_permissions',$user['User']['id'],$controller_name,$plugin),array('class'=>'controller')));
					}
					$buffer.=$this->Html->tag("/ul",null);							
					echo $this->Html->tag("li",$this->Html->tag("h4",$plugin).$buffer,"plugin");
				}
			}
		echo $this->Html->tag("/ul",null);
		echo $this->Html->tag("li",$this->Html->tag("h3",$level).ob_get_clean(),'level');
	}
	
	echo $this->Html->tag("/ul",null);

	echo $html->image('/acl/img/design/tick.png') . ' ' . 'authorized';
	echo '&nbsp;&nbsp;&nbsp;';
	echo $html->image('/acl/img/design/cross.png') . ' ' .'blocked';
	?>
</div>
<?php    
}
?>
<?php
echo $this->element('design/footer');
?>