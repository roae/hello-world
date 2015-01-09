<?php
	Router::connect('/admin/media/upload',array('controller'=>'files','action'=>'add_files','plugin'=>'media','admin'=>true));
	Router::connect('/admin/media/add_folder/*', array('controller' => 'files', 'action' => 'add_folder','plugin'=>'media','admin'=>true));
	Router::connect('/admin/media/tiny_images/*', array('controller' => 'files', 'action' => 'tiny_images','plugin'=>'media','admin'=>true));
	Router::connect('/admin/media/*', array('controller' => 'files', 'action' => 'index','plugin'=>'media','admin'=>true));
?>
