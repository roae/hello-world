<div class="fileViewer">
	<div class="fileContainer">
		<?php
			foreach($files as $file){
		?>
			<a class="file" href="/admin/media<?= $file['Upload']['path'] ?>" rel="<?= $file['Upload']['id']?>" >
				<span class="align-center">
					<span class="thumbnail <?= ($file['Upload']['mime']!="folder")? "image" : "" ?>">
						<?php
							echo $this->Html->image($file['Upload']['thumb'],array('alt'=>'','class'=>($file['Upload']['mime']!="folder")? "image" : ""));
						?>
					</span>
				</span>
				<span class="filename"><?= ($file['Upload']['mime']!='folder')? $this->Text->truncate($file['Upload']['name'],15,array('ending'=>'..','exact'=>true)).'.'.$file['Upload']['extension'] :$this->Text->truncate($file['Upload']['name'],15,array('ending'=>'..','exact'=>true)) ?></span>
				<span class="size"><?= ($file['Upload']['mime']!="folder")? $this->Number->toReadableSize($file['Upload']['size']).' '.$file['Upload']['width'].'x'.$file['Upload']['height'] : ''; ?></span>
			</a>
		<?php
			}
		?>
	</div>
</div>