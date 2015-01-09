<h2>[:add_group:]</h2>
<?php
echo $this->Form->create('Group', array('url' => array('controller' => 'groups', 'action' =>'admin_add')));
	echo $this->Form->input('Group.parent_id');
	echo $this->Form->input('Group.name');
echo $this->Form->end('[:save:]');
?>