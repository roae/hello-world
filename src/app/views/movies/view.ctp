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
			<header>
				<h1 class="blured-title">
					<?= Inflector::humanize( low( $record['Movie']['title'] ) ) ?>
				</h1>

				<p><?= Inflector::humanize( low( $record['Movie']['original_title'] ) ) ?></p>
			</header>

			<!--span class="likes">65</span-->
		</div>
	</div>

	<div class="movie-information">
		<div class="col-container">

			<div class="main-content">
				<div class="sinopsis">
					<div class="cover-container">
						<?= $this->Html->image( $record['Poster']['medium'], array( 'alt' => '[:logo_alt:]' ) ) ?>

						<a class="like" href="">Me gusta</a>
					</div>

					<h2>[:movie-sinopsis:]</h2>

					<?= $record['Movie']['synopsis'] ?>

				</div>

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

				<?= $this->element("shows/movie");?>

			</div>

				<aside class="movie-detailed-info">
					<?php if( $record['Movie']['trailer'] != '' ): ?>
						<a class="pause-flag" href="#"></a>
						<a class="watch-trailer trailer-trigger" href="<?= $record['Movie']['trailer'] ?>">[:see-movie-trailer:]</a>
					<?php endif; ?>

					<?php if( $record['Movie']['duration'] != '' ): ?>
						<div class="info">
							<strong>[:movie-duration:]:</strong>
							<span class="value"><?= $record['Movie']['duration'] ?> mins.</span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['clasification'] != '' ): ?>
						<div class="info">
							<strong>[:movie-clasification:]:</strong>
							<span class="value"><?= $record['Movie']['clasification'] ?></span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['genre'] != '' ): ?>
						<div class="info">
							<strong>[:movie-genre:]:</strong>
							<span class="value"><?= $record['Movie']['genre'] ?></span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['director'] != '' ): ?>
						<div class="info">
							<strong>[:movie-director:]:</strong>
							<span class="value"><?= $record['Movie']['director'] ?></span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['actors'] != '' ): ?>
						<div class="info">
							<strong>[:movie-actors:]:</strong>
			  <span class="value">
				<?= $record['Movie']['actors'] ?>
			  </span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['language'] != '' ): ?>
						<div class="info">
							<strong>[:movie-lang:]:</strong>
							<span class="value"><?= $record['Movie']['language'] ?></span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['nationality'] != '' ): ?>
						<div class="info">
							<strong>[:movie-nationality:]:</strong>
							<span class="value"><?= $record['Movie']['nationality'] ?></span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['music_director'] != '' ): ?>
						<div class="info">
							<strong>[:movie-music-director:]:</strong>
							<span class="value"><?= $record['Movie']['music_director'] ?></span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['photografy_director'] != '' ): ?>
						<div class="info">
							<strong>[:movie-picture-director:]:</strong>
							<span class="value"><?= $record['Movie']['photografy_director'] ?></span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['year'] != '' ): ?>
						<div class="info">
							<strong>[:movi-year:]:</strong>
							<span class="value"><?= $record['Movie']['year'] ?></span>
						</div>
					<?php endif; ?>

					<?php if( $record['Movie']['website'] != '' ): ?>
						<div class="info">
							<strong>[:movire-siteweb:]:</strong>
							<span
								class="value"><?= $this->Html->link( '[:movie_website:]', $record['Movie']['website'] ) ?></span>
						</div>
					<?php endif; ?>

					<div class="vertical-banner">
						<?= $this->element("ads/show",array('type'=>'VERTICALMINI'));?>
					</div>
				</aside>

			</div>
		</div>
	</div>
</div>

<?php
$this->Html->script( array(
	'ext/images-loaded.min.js',
	'ext/litebox.min.js',
), false );
?>