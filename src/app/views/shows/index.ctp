<?php
$this->Html->script(array('ext/jquery.history.js','ext/jquery.history.html45.js','ext/jquery.flydom.js',"ext/jquery.xupdater.js"),array('inline'=>false));
?>

<section class="billboard-container">
	<div id="loading">
		<div class="message">
			<i class="icon-loading"></i>
			<div>[:cargando-cartelera:]</div>
		</div>
	</div>
	<div class="col-container">

		<?php echo $this->Ajax->div("topMessage",array('class'=>'top-message')); ?>
			<h1>Cartelera de <?= Configure::read("CitySelected.name") ?></h1>
			<p>Selecciona un horario para comprar tus boletos</p>
		<?php echo $this->Ajax->divEnd("topMessage"); ?>

		<div class="the-billboard">
			<div class="billboard-aside">

				<?= $this->element("shows/filter"); ?>

				<div class="vertical-banner">
					<?= $this->element("ads/show",array('type'=>'VERTICAL','cache' => array('key' => 'vertical', 'time' => '+1 day')));?>
				</div>
			</div>
			<?php
			echo $this->Ajax->div("Billboard",array('class'=>'billboard-list'));
			$this->I18n->start();
			foreach($billboard as $record): ?>
				<div class="complex">
					<div class="complex-name floating">
						<a name="<?= Inflector::slug($record['Location']['name'],"-") ?>"></a>
						<span class="complex-label"><?= $record['Location']['name'] ?></span>
					</div>
					<?php
					if(!$record['Location']['venta_online']){
						echo $this->Html->tag("div","[:mensaje_venta_online_no_dispobible:]",'noOnline');
					}
					if(isset($record['Show'])){
					?>
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
												if($record['Location']['venta_online']){
													echo $this->Html->tag("li",
														$this->Html->link(
															$this->Time->format("h:i ",$show['schedule']).
															$this->Html->tag("small",$this->Time->format("a",$show['schedule'])).
															$this->Html->tag("span",
																$this->Html->tag("span",$show['screen_name'],"room").
																($show['Projection']['format'] == "3D" ? $this->Html->tag("span","3D","format") : "").
																($show['room_type'] == "mega" ? $this->Html->tag("span","MEGAPANTALLA","room_type") : "")
																,"details"),
															array('controller'=>'shows','action'=>'buy','show_id'=>$show['id'],'movie_slug'=>$item['Movie']['slug']),
															array('escape'=>false)
														));
												}else{
													echo $this->Html->tag("li",
														$this->Html->tag("span",
															$this->Time->format("h:i ",$show['schedule']).
															$this->Html->tag("small",$this->Time->format("a",$show['schedule'])).
															$this->Html->tag("span",
																$this->Html->tag("span",$show['screen_name'],"room").
																($show['Projection']['format'] == "3D" ? $this->Html->tag("span","3D","format") : "").
																($show['room_type'] == "mega" ? $this->Html->tag("span","MEGAPANTALLA","room_type") : "")
																,"details"),
															array('class'=>'show')
														));
												}

											}
											?>
										</ul>
									</div>
								<? endforeach; ?>
								<? if(isset($item['Premier'])){ ?>
									<strong class="schedule-title">[:PREMIERE:]</strong>
									<? foreach($item['Premier'] as $type => $shows): ?>
										<div class="schedule premiere">
											<span class="label"><strong><?= str_replace("|","/",$type) ?></strong></span>
											<ul>
												<?
												$schedule = array();
												foreach($shows as $show){
													if($record['Location']['venta_online']){
														echo $this->Html->tag("li",
															$this->Html->link(
																$this->Time->format("h:i ",$show['schedule']).
																$this->Html->tag("small",$this->Time->format("a",$show['schedule'])).
																$this->Html->tag("span",
																	$this->Html->tag("span",$show['screen_name'],"room").
																	($show['Projection']['format'] == "3D" ? $this->Html->tag("span","3D","format") : "").
																	($show['room_type'] == "mega" ? $this->Html->tag("span","MEGAPANTALLA","room_type") : "")
																	,"details"),
																array('controller'=>'shows','action'=>'buy','show_id'=>$show['id'],'movie_slug'=>$item['Movie']['slug']),
																array('escape'=>false)
															));
													}else{
														echo $this->Html->tag("li",
															$this->Html->tag("span",
																$this->Time->format("h:i ",$show['schedule']).
																$this->Html->tag("small",$this->Time->format("a",$show['schedule'])).
																$this->Html->tag("span",
																	$this->Html->tag("span",$show['screen_name'],"room").
																	($show['Projection']['format'] == "3D" ? $this->Html->tag("span","3D","format") : "").
																	($show['room_type'] == "mega" ? $this->Html->tag("span","MEGAPANTALLA","room_type") : "")
																	,"details"),
																array('class'=>'show')
															));
													}
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
					<div class="endComplexWaypoint"></div>
					<?php }else{ ?>
						<div class="no-movies">
							<div class="big">[:no-movies-to-show-in-location:]</div>
							<div>[:try-other-day:]</div>
							<?= $this->Html->link("Ver horario de mañana","#",array('class'=>'btn'));?>
						</div>
					<?php } ?>

				</div>
				<?php endforeach;
			echo $this->Html->tag("div","","endBillboard");
			$this->I18n->end();
			echo $this->Ajax->divEnd("Billboard");
			?>

		</div>
		<section class="horizontal-banner">
			<?= $this->element("ads/show",array('type'=>'HORIZONTAL','cache' => array('key' => 'horizontal', 'time' => '+1 day'))); ?>
		</section>

	</div>
</section>
<?= $this->element("movies/commingsoon",array('cache' => array('key' => isset($CitySelected['name']) ? $CitySelected['name'] : "", 'time' => '+1 hour'))); ?>

