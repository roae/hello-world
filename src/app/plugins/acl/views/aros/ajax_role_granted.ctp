<?php 
    
    if(isset($acl_error))
    {
        $title = isset($acl_error_aro) ? __d('acl', 'The role node does not exist in the ARO table', true) : __d('acl', 'The ACO node is probably missing. Please try to rebuild the ACOs first.', true) ;
        echo $html->image('/acl/img/design/important16.png', array('class' => 'pointer', 'alt' => $title, 'title' => $title));
    }
    else
    {
    	echo $this->Html->link("",
			$this->Html->url('/')."admin/acl/aros/deny_role_permission/" . $this->params['pass'][0] . "/plugin:" . $this->params['named']['plugin'] . "/controller:" . $this->params['named']['controller'] . "/action:" . $this->params['named']['action'],
			array('class'=>'TogglePermision grant','escape'=>false)
		);
    }
    
?>