<?php
/* @var $this View */
$this->Html->script(array('ext/jquery.history.js','ext/jquery.history.html45.js','ext/jquery.flydom.js',"ext/jquery.xupdater.js"),array('inline'=>false));
$this->set("pageDescription",$this->Text->truncate($record['Movie']['synopsis'],150));
$this->set("pageKeywords",$record['Movie']['title']);
?>
<div class="movie-detail-container">

	<?php
	$bg_url = '';
	$class = '';

	if( isset( $record['Gallery'][0]['url'] ) ){
		$bg_url = $record['Gallery'][0]['url'];
	} else{
		$class = 'image-not-founded';
	}
	?>

	<div class="big-cover <?= $class ?>" style="background-image: url(<?= $bg_url ?>)">
		<div class="col-container">
			<div class="movie-title">
				<h1 class="blured-title">
					<?= h($record['Movie']['title']) ?>
				</h1>

				<p><?= h($record['Movie']['original_title']) ?></p>
			</div>
			<!--<span class="presale">[:presale:]</span>-->
			<!--span class="likes">65</span-->
		</div>
	</div>

	<div class="movie-information">
		<div class="col-container">

			<div class="main-content">
				<div class="cover-container">
					<?= $this->Html->image( $record['Poster']['medium'], array( 'alt' => '[:logo_alt:]' ) ) ?>
					<!--<a class="like" href="">Me gusta</a>-->
				</div>

				<div class="movilTrailer">
					<?php if( $record['Movie']['trailer'] != '' ): ?>
					<a class="pause-flag" href="#"></a>
					<a class="watch-trailer trailer-trigger" href="<?= $record['Movie']['trailer'] ?>">[:see-movie-trailer:]</a>
					<?php endif; ?>
					<!--<a class="like" href="">Me gusta</a>-->
				</div>



				<div id="sinopsisTab" class=" selected">
					<h2>[:movie-sinopsis:]</h2>
					<?= $record['Movie']['synopsis'] ?>
				</div>

				<?php if(count($record['Gallery'])>1){ ?>
					<div id="galleryTab" class="">
						<h2>[:movie-gallery:]</h2>
						<div class="movie-gallery-container">

							<div class="movie-gallery-carousel">
								<?php foreach( $record['Gallery'] as $image ){ ?>

									<a href="<?= $image['url'] ?>" class="litebox" data-litebox-group="group-1">
										<?= $this->Html->image( $image['thumb'], '' ) ?>
									</a>

								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<div id="scheduleTab" class="">
					<?= $this->element("shows/movie");?>
				</div>
			</div>
			<aside class="movie-detailed-info" id="detailsTab">
				<?= $this->element("movies/details-info");?>
			</aside>

		</div>
	</div>
</div>


<?php
$this->Html->script( array(
	'ext/images-loaded.min.js',
	'ext/litebox.min.js',
), false );
?>