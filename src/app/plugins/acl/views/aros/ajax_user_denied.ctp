<?php 
echo '<span id="right_' . $plugin . '_' . $user_id . '_' . $controller_name . '_' . $action . '">';

	if(isset($acl_error))
	{
		if(isset($acl_error_aro))
		{
			$title = __d('acl', 'The user node does not exist in the ARO table', true);
		}
		elseif(isset($acl_error_aco))
		{
			$title = __d('acl', 'The ACO node is probably missing. Please try to rebuild the ACOs first.', true);
		}
		else
		{
			$title = __d('acl', 'The ARO or the ACO node is probably missing. Please try to rebuild the ACOs first.', true);	
		}
		
		echo $html->image('/acl/img/design/important16.png', array('class' => 'pointer', 'alt' => $title, 'title' => $title));
	}
	else
	{
		echo $this->Html->link("",
			$this->Html->url('/')."admin/acl/aros/grant_user_permission/" . $this->params['pass'][0] . "/plugin:" . $this->params['named']['plugin'] . "/controller:" . $this->params['named']['controller'] . "/action:" . $this->params['named']['action'],
			array('class'=>'TogglePermision deny','escape'=>false)
		);
	}

echo '</span>';
?>