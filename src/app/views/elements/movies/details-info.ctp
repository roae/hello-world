
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