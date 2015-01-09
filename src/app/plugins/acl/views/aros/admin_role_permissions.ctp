<?php
//echo $this->Html->script('/acl/js/jquery');
$this->Html->script('/acl/js/acl_plugin',array('inline'=>false));

echo $this->element('design/header');
?>

<?php
echo $this->element('aros/links');
?>

<div class="separator"></div>

<div>
	
	<?php
	echo $html->link($this->Html->tag("span","",'icon-remove') . ' ' . 'Clear permissions table', '/admin/acl/aros/empty_permissions', array('confirm' => 'Are you sure you want to delete all roles and users permissions ?', 'escape' => false,'class'=>'btn_danger'));
	?>
	
	
</div>
<div class="separator"></div>
<div class="permissions">

	<table cellspacing="0">

	<tr>
		<th>Group</th>
		<th><?php echo 'grant access to <em>all actions</em>'; ?></th>
		<th><?php echo 'deny access to <em>all actions</em>'; ?></th>
	</tr>

	<?php
	$i = 0;
	foreach($roles as $role)
	{
		$color = ($i % 2 == 0) ? 'color1' : 'color2';
		echo '<tr>';
		echo '  <td>' . $role[$role_model_name][$role_display_field] . '</td>';
		echo '  <td>' . $html->link($this->Html->tag("span","","icon-ok"), '/admin/acl/aros/grant_all_controllers/' . $role[$role_model_name]['id'], array('escape' => false,'class'=>'btn_success' ,'confirm' => sprintf("Are you sure you want to grant access to all actions of each controller to the role '%s' ?", $role[$role_model_name][$role_display_field]))) . '</td>';
		echo '  <td>' . $html->link($this->Html->tag("span","","icon-remove"), '/admin/acl/aros/deny_all_controllers/' . $role[$role_model_name]['id'], array('escape' => false,'class'=>'btn_danger' , 'confirm' => sprintf("Are you sure you want to deny access to all actions of each controller to the role '%s' ?", $role[$role_model_name][$role_display_field]))) . '</td>';
		echo '</tr>';
		
		$i++;
	}
	?>
	</table>

	<?php
	echo $this->Html->tag("ul",null);
	foreach($controllers as $level=>$_controllers){
		ob_start();
		echo $this->Html->tag("ul",null);
			foreach($_controllers as $plugin=>$_controller){
				if(is_string($_controller)){
					echo $this->Html->tag("li",$this->Html->link($_controller,array('action'=>'controller_role_permissions',$_controller),array('class'=>'controller')));
				}else{
					$buffer="";	
					$buffer.=$this->Html->tag("ul",null);
					foreach($_controller as $controller_name){
						$buffer.=$this->Html->tag("li",$this->Html->link($controller_name,array('action'=>'controller_role_permissions',$controller_name,$plugin),array('class'=>'controller')));
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

<div>


<?php
echo $this->element('design/footer');
?>