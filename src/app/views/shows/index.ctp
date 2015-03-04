

<section class="billboard-container">
	<div class="col-container">

		<div class="top-message">
			<h1>Cartelera de <?= Configure::read("CitySelected.name") ?></h1>
			<p>Selecciona un horario para comprar tus boletos</p>
		</div>

		<div class="the-billboard">
			<div class="billboard-list">
			<?php foreach($billboard as $record): ?>
				<div class="complex">
					<div class="complex-name floating">
						<span class="complex-label"><?= $record['Location']['name'] ?></span>
					</div>

					<ul class="movies">
					<?php
						foreach($record['Show'] as $item) {
					?>

						<li class="movie">
							<a name="<?= $item['Movie']['slug'] ?>" class="movieAnchor"></a>
							<div class="image-container">
								<?= $this->Html->link($this->Html->image($this->Uploader->generatePath($item['Movie']['Poster'],'medium')), array('controller' => 'movies', 'action' => 'view', 'slug' => $item['Movie']['slug']), array('escape' => false));?>
							</div>

						<div class="schedules">
							<div class="movie-title">
								<h3>
									<?= $this->Html->link($item['Movie']['title'], array('controller' => 'movies', 'action' => 'view', 'slug' => $item['Movie']['slug']), array('escape' => false)); ?>
								</h3>
								<span class="clasification-duration"><?php if( $item['Movie']['clasification'] != '' || $item['Movie']['duration'] ): ?>(<?= ($item['Movie']['clasification'] != '') ? $item['Movie']['clasification'].' | ' : '' ?><?= ($item['Movie']['duration'] != '') ? $item['Movie']['duration'].' mins' : '' ?>)<?php endif; ?></span>
								<strong class="real-name"><?= $item['Movie']['original_title'] ?></strong>
								</div>
								<strong class="schedule-title">Horarios</strong>
								<? foreach(isset($item['Normal']) ? $item['Normal'] : array() as $type => $shows): ?>
									<div class="schedule">
										<span class="label"><strong><?= str_replace("|","/",$type) ?></strong></span>
										<ul>
											<?
											$schedule = array();
											foreach($shows as $show){
												echo $this->Html->tag("li",$this->Html->link($this->Time->format("H:i",$show['schedule']),array('controller'=>'shows','action'=>'buy','show_id'=>$show['id'],'movie_slug'=>$item['Movie']['slug']),array('title'=>$show['screen_name'])));
											}
											?>
										</ul>
									</div>
								<? endforeach; ?>
								<? if(isset($item['Premier'])){ ?>
									<strong class="schedule-title">[:PREMIERE:]</strong>
									<? foreach($item['Premier'] as $type => $shows): ?>
										<div class="schedule premiere">
											<span class="label"><strong><?= str_replace("|","/",$type) ?></span>
											<ul>
												<?
												$schedule = array();
												foreach($shows as $show){
													echo $this->Html->tag("li",$this->Html->link($this->Time->format("H:i",$show['schedule']),array('controller'=>'shows','action'=>'buy','show_id'=>$show['id'],'movie_slug'=>$item['Movie']['slug']),array('title'=>$show['screen_name'])));
												}
												?>
											</ul>
										</div>
								<?
									endforeach;
								}
								?>

							</div>

						</li>

					<?php } ?>
					</ul>

				</div>
				<?php endforeach; ?>
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
