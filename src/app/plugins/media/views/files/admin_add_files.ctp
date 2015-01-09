<?php
	echo sprintf('var $response = %s;',$javascript->object(compact('data','errors')));
?>