<div id="MediaUpload" class="MediaUploader multiUploads">
	<input id="Upload" class="UploadInput" type="file" value="" name="data[Media][Upload]">
	<?php
		Configure::write('Media.Upload.config',normalizeAllowedConfig(Configure::read('Media.Upload.config')));
		echo $this->Form->hidden('config',array('id'=>'UploadConfig','value'=>addslashes($this->Js->object(Configure::read('Media.Upload.config')))));
		echo $this->Form->hidden('data',array('id'=>'UploadData','value'=>addslashes($this->Js->object($this->data['Upload']))));
	?>
</div>