<div class="InfoPanel">
	<button type="button" class="close"></button>
	<span class="title">[:file_information:]</span>
	<div class="infos">
		<?php
		foreach((array)$this->getVar('infoFiles') as $infoFile){
			echo $infoFile;
		}
		?>
	</div>
</div>
