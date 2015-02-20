

<section class="billboard-container">
	<div class="col-container">

		<div class="top-message">
			<header>
				<h1>Cartelera de <?= Configure::read("LocationSelected.name")?></h1>
			</header>

			<p>Selecciona un horario para comprar tus boletos</p>
		</div>

		<div class="the-billboard">

			<ul class="billboard-list">

			<?php
				$locationSelected = Configure::read("LocationSelected");
				$conditions = array();

				if(Configure::read("LocationSelected.id")){
					$conditions = array('Show.location_id'=>Configure::read("LocationSelected.id"));
				}

				$query = array(
					'fields'=>array('Show.id'),
					'contain'=>array(
						'Movie'=>array(
							'fields'=>array('Movie.id','Movie.title', 'Movie.genre', 'Movie.duration'),
							'Poster'
						)
					),
					'group'=>array(
						'movie_id'
					),
					'conditions'=>$conditions
				);

				$billboard = $this->requestAction(array('controller'=>'shows','action'=>'get','type'=>'all','query'=>$query));

				foreach($billboard as $item) {
			?>

				<li class="movie">

					<div class="image-container">
						<?= $this->Html->image($this->Uploader->generatePath($item['Movie']['Poster'],'medium'));?>
					</div>

					<div class="schedules">
						<div class="movie-title">
							<?= $this->Html->tag("h2", $item['Movie']['title'])?>

							<span class="clasification-duration">(B15 | 126 mins)</span>
							<strong class="real-name">Nombre real</strong>
						</div>

						<?php
							/*foreach($item['Show'] as $type=>$shows):

								echo $this->Html->tag("h4", Inflector::humanize($type));
								$schedule = array();

								foreach($shows as $show){
									$schedule[]= $this->Time->format("H:i",$show['schedule']);
								}

								echo $this->Html->tag("div", implode(" | ",$schedule));

							endforeach;*/
						?>

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

				</li>

			<?php } ?>

			</ul>

		</div>

	</div>
</section>
