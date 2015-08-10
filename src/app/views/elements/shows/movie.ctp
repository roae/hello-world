<?php
/* @var $this View */
echo $this->Ajax->div("BuyTickets",array('class'=>'buy-tickets')); $this->I18n->start();
?>
	<a name="horarios"></a>
	<div id="loading">
		<div class="message">
			<i class="icon-loading"></i>
			<div>[:cargando-horarios-pelicula:]</div>
		</div>
	</div>
	<h2>No hagas fila, compra tus boletos en línea</h2>
	<?= $this->element("shows/filter",array('movie_id'=>$record['Movie']['id'])); ?>
	<div class="billboard-list">
		<?php if(!empty($billboard)){?>
			<h3>Selecciona un horario para comprar tus boletos</h3>
			<?php foreach($billboard as $record):
				$presale = "";
				if(isset($record['Show'])){
					//pr($record['Show']);
					$_shows = $record['Show'];
					$firstShow = array_shift($_shows);
					$fisrtMovie = array_shift($firstShow);
					//$presale = $fisrtMovie['Movie']['MovieLocation'] =
					#pr($fisrtMovie);
					$MovieLocation = isset($fisrtMovie['MovieLocation'][0]) ? $fisrtMovie['MovieLocation'][0] : null;

					if($MovieLocation && $MovieLocation['presale']){

						list($start_year,$start_month,$start_day) = explode("-",$MovieLocation['presale_start']);
						list($end_year,$end_month,$end_day) = explode("-",$MovieLocation['presale_end']);
						$presale_start = mktime(0,0,0,$start_month,$start_day,$start_year);
						$presale_end = mktime(0,0,0,$end_month,$end_day,$end_year);
						$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
						if($today >= $presale_start && $today <= $presale_end){
							$presale = $this->Html->tag("span","[:presale:]",'presale');
						}
					}
				}
				?>
				<div class="complex">
					<div class="complex-name floating">
						<span class="complex-label"><?= $record['Location']['name'].$presale ?></span>
					</div>
					<?php
					if(!$record['Location']['venta_online']){
						echo $this->Html->tag("div","[:mensaje_venta_online_no_dispobible:]",'noOnline');
					}
					if(isset($record['Show'])){
						foreach($record['Show'] as $item) {
							?>
							<div class="schedules">
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
						<?php
						}
					}else{ ?>
						<div class="no-schedules">
							<div class="big">[:no-movies-to-show-in-location:]</div>
							<div>[:try-other-day:]</div>
							<? // echo $this->Html->link("Ver horario de mañana","#",array('class'=>'btn'));?>
						</div>
					<?php } ?>
				</div>
		<?php
			endforeach;
		}else{
		?>
			<div class="noCity">
				[:Selecciona-tu-ciudad-para-horarios:]
			</div>
		<?php
		}
		?>
	</div>
<?php
echo $this->I18n->end();
echo $this->Ajax->divEnd("BuyTickets");
$this->I18n->addMissing("[:cargando-horarios-pelicula:]","Mensaje que aparece cuando se cambia el filtro de horarios");
$this->I18n->addMissing("[:Selecciona-tu-ciudad-para-horarios:]","Mensaje que aparece cuando no se ha seleccionado una ciudad en la seccion de horarios");

?>