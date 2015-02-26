

<section class="billboard-container">
	<div class="col-container">

		<div class="top-message">
			<header>
				<h1>Cartelera de <?= Configure::read("CitySelected.name") ?></h1>
			</header>

			<p>Selecciona un horario para comprar tus boletos</p>
		</div>

		<div class="the-billboard">

			<div class="billboard-list">
				<h2 class="complex">La Isla</h2>

				<ul>
				<?php
					foreach($billboard as $item) {
				?>

					<li class="movie">

						<div class="image-container">
							<?= $this->Html->link($this->Html->image($this->Uploader->generatePath($item['Movie']['Poster'],'medium')), array('controller' => 'movies', 'action' => 'view', 'id' => $item['Movie']['id'], 'slug' => Inflector::slug(low($item['Movie']['title']), '-')), array('escape' => false));?>
						</div>

						<div class="schedules">
							<div class="movie-title">
								<h3>
									<?= $this->Html->link($item['Movie']['title'], array('controller' => 'movies', 'action' => 'view', 'id' => $item['Movie']['id'], 'slug' => Inflector::slug(low($item['Movie']['title']), '-')), array('escape' => false)); ?>
								</h3>

								<span class="clasification-duration">(B15 | 126 mins)</span>
								<strong class="real-name">Nombre real</strong>
							</div>
							<strong class="schedule-title">Horarios</strong>
							<? foreach($item['Show'] as $type => $shows): ?>
								<div class="schedule">
									<span class="label"><strong><?= Inflector::humanize($type) ?></strong></span>

									<ul>
										<?
										$schedule = array();
										foreach($shows as $show){
											echo $this->Html->tag("li",$this->Html->link($this->Time->format("H:i",$show['schedule']),array('controller'=>'shows','action'=>'buy','session_id'=>$show['session_id'])));
										}
										?>
									</ul>
								</div>
							<? endforeach; ?>

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

					</li>

				<?php } ?>
				</ul>

			</div>

			<div class="billboard-aside">

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

					<span class="label">Ciudad</span>
					<div class="filter-select">
						<a href="">Culiacán</a>
					</div>

					<span class="label">Complejos seleccionados</span>

					<a class="selected-complex selected" href="">La Isla</a>
					<a class="selected-complex" href="">Galerías San Miguel</a>

				</div>

				<div class="vertical-banner">
					<?= $this->Html->image("refill-vertical.png",array('alt'=>'[:logo_alt:]')) ?>
				</div>
			</div>

		</div>

	</div>
</section>
