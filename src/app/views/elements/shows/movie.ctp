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

	<div class="billboard-list">
		<?php foreach($billboard as $record): ?>
			<div class="complex">
				<div class="complex-name floating">
					<span class="complex-label"><?= $record['Location']['name'] ?></span>
				</div>
				<?php
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
											echo $this->Html->tag("li",$this->Html->link($this->Time->format("h:i <\s\m\a\l\l>a</\s\m\a\l\l>",$show['schedule']),array('controller'=>'shows','action'=>'buy','show_id'=>$show['id'],'movie_slug'=>$item['Movie']['slug']),array('title'=>$show['screen_name'],'escape'=>false)));
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
												echo $this->Html->tag("li",$this->Html->link($this->Time->format("h:i <\s\m\a\l\l>a</\s\m\a\l\l>",$show['schedule']),array('controller'=>'shows','action'=>'buy','show_id'=>$show['id'],'movie_slug'=>$item['Movie']['slug']),array('title'=>$show['screen_name'],'escape'=>false)));
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
						<div class="big">[:no-schedules-in-location:]</div>
						<div>[:try-other-day:]</div>
						<?= $this->Html->link("Ver horario de mañana","#",array('class'=>'btn'));?>
					</div>
				<?php } ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>