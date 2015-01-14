<?php
/* @var $this View */
echo $this->Html->css("ext/lightbox");
echo $this->Html->script("ext/lightbox.min",array('inline'=>false));

if(!empty($recordset)){ ?>
	<ul class="Gallery">
		<?php
		foreach($recordset as $record){
			?>
			<li>
				<span class="imgThumb">
					<a href="<?= $record['url'] ?>" data-lightbox="gallery"><?= $this->Html->image($record['thumb']);?></a>
				</span>
			</li>
			<?php
		}
		?>
	</ul>
<?php } ?>