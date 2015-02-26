<div class="movie-detail-container">

  <?php
	$bg_url = '';
	$class = '';

	if( isset($record['Gallery'][0]['url']) ) {
	  $bg_url = $record['Gallery'][0]['url'];
	} else {
	  $class = 'image-not-founded';
	}
  ?>

  <div class="big-cover <?= $class ?>" style="background-image: url(<?= $bg_url ?>)">
	<div class="col-container">
	  <header>
		<h1 class="blured-title">
		  <?= Inflector::humanize(low($record['Movie']['title'])) ?>
		</h1>
		<p><?= Inflector::humanize(low($record['Movie']['original_title'])) ?></p>
	  </header>

	  <span class="likes">65</span>
	</div>
  </div>

  <div class="movie-information">
	<div class="col-container">

	  <div class="sinopsis">
		<div class="cover-container">
		  <?= $this->Html->image($record['Poster']['medium'], array('alt'=>'[:logo_alt:]')) ?>
		</div>

		<h2>Sinopsis</h2>

		<?= $record['Movie']['synopsis'] ?>

		<div class="movie-gallery-container">
		  <div class="movie-gallery-carousel">
			<?php foreach ($record['Gallery'] as $image) { ?>

			  <a href="<?= $image['url'] ?>" class="litebox" data-litebox-group="group-1">
				<?= $this->Html->image($image['thumb'], '') ?>
			  </a>

			<?php } ?>
		  </div>
		</div>

		<div class="buy-tickets">
		  <h2>No hagas fila, compra tus boletos en línea</h2>
		  <p>Selecciona un horario para comprar tus boletos</p>

		  <div class="filter">
			<div class="input">
			  <span class="label">Fecha de cartelera</span>
			  <div class="filter-select">
				<a href="">09/02/2014 <strong>(Hoy)</strong></a>

				<ul>
				  <li>
					<a href="">Opción #1</a>
				  </li>

				  <li>
					<a href="">Opción #2</a>
				  </li>
				</ul>
			  </div>
			</div>

			<div class="input">
			  <span class="label">Ciudad</span>
			  <div class="filter-select">
				<a href="">Culiacán</a>
			  </div>
			</div>

			<span class="label">Complejos seleccionados</span>

			<a class="selected-complex selected" href="">La Isla</a>
			<a class="selected-complex" href="">Galerías San Miguel</a>
		  </div>

		  <h3>La Isla</h3>

		  <div class="billboard-list">
			<div class="schedules">

			  <strong class="schedule-title">Horarios</strong>

			  <div class="schedule">
				<span class="label"><strong>SUB</strong>/DIG</span>

				<ul>
				  <li>
					<a href="">16:45</a>
				  </li>

				  <li>
					<a href="">16:55</a>
				  </li>

				  <li>
					<a href="">16:55</a>
				  </li>

				  <li>
					<a href="">18:20</a>
				  </li>

				  <li>
					<a href="">18:40</a>
				  </li>

				  <li>
					<a href="">20:35</a>
				  </li>

				  <li>
					<a href="">22:15</a>
				  </li>
				</ul>
			  </div>

			  <div class="schedule">
				<span class="label"><strong>DOB</strong>/DIG</span>

				<ul>
				  <li>
					<a href="">18:20</a>
				  </li>

				  <li>
					<a href="">20:35</a>
				  </li>

				  <li>
					<a href="">23:20</a>
				  </li>
				</ul>
			  </div>

			  <div class="schedule">
				<span class="label"><strong>SUB</strong>/3D</span>

				<ul>
				  <li>
					<a href="">15:10</a>
				  </li>

				  <li>
					<a href="">19:10</a>
				  </li>
				</ul>
			  </div>

			  <strong class="schedule-title">PREMIERE</strong>

			  <div class="schedule premiere">
				<span class="label"><strong>SUB</strong>/DIG</span>

				<ul>
				  <li>
					<a href="">18:20</a>
				  </li>

				  <li>
					<a href="">20:35</a>
				  </li>

				  <li>
					<a href="">23:20</a>
				  </li>
				</ul>
			  </div>
			</div>
			</div>
		  </div>

		</div>

		<aside class="movie-detailed-info">
		  <?php if( $record['Movie']['trailer'] != '' ): ?>
			<a class="pause-flag" href="#"></a>
			<a class="watch-trailer trailer-trigger" href="<?= $record['Movie']['trailer'] ?>">Ver trailer</a>
		  <?php endif; ?>

		  <?php if( $record['Movie']['duration'] != '' ): ?>
			<div class="info">
			  <strong>Duración:</strong>
			  <span class="value"><?= $record['Movie']['duration'] ?> mins.</span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['clasification'] != '' ): ?>
			<div class="info">
			  <strong>Clasificación:</strong>
			  <span class="value"><?= $record['Movie']['clasification'] ?></span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['genre'] != '' ): ?>
			<div class="info">
			  <strong>Género:</strong>
			  <span class="value"><?= $record['Movie']['genre'] ?></span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['director'] != '' ): ?>
			<div class="info">
			  <strong>Director(es):</strong>
			  <span class="value"><?= $record['Movie']['director'] ?></span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['actors'] != '' ): ?>
			<div class="info">
			  <strong>Actor(es):</strong>
			  <span class="value">
				<?= $record['Movie']['actors'] ?>
			  </span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['language'] != '' ): ?>
			<div class="info">
			  <strong>Idioma:</strong>
			  <span class="value"><?= $record['Movie']['language'] ?></span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['nationality'] != '' ): ?>
			<div class="info">
			  <strong>Nacionalidad:</strong>
			  <span class="value"><?= $record['Movie']['nationality'] ?></span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['music_director'] != '' ): ?>
			<div class="info">
			  <strong>Música:</strong>
			  <span class="value"><?= $record['Movie']['music_director'] ?></span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['photografy_director'] != '' ): ?>
			<div class="info">
			  <strong>Fotografía:</strong>
			  <span class="value"><?= $record['Movie']['photografy_director'] ?></span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['year'] != '' ): ?>
			<div class="info">
			  <strong>Año:</strong>
			  <span class="value"><?= $record['Movie']['year'] ?></span>
			</div>
		  <?php endif; ?>

		  <?php if( $record['Movie']['website'] != '' ): ?>
			<div class="info">
			  <strong>Sitio web oficial:</strong>
			  <span class="value"><?= $this->Html->link('[:movie_website:]', $record['Movie']['website']) ?></span>
			</div>
		  <?php endif; ?>

		  <div class="vertical-banner">
			<?= $this->Html->image("refill-vertical-small.png",array('alt'=>'[:logo_alt:]')) ?>
		  </div>
		</aside>

	  </div>
	</div>
  </div>
</div>

<?php
	$this->Html->script(array(
		'ext/images-loaded.min.js',
		'ext/litebox.min.js',
	), false);
?>