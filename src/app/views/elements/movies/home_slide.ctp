<?php
/* @var $this View */
$movies = $this->requestAction(array('controller'=>'movies','action'=>'get','type'=>'all','query'=>array(
	'conditions'=>array('Movie.home'=>1,'Movie.status'=>1,'Movie.trash'=>0),
	'contain'=>array(
		'Gallery'=>array('limit'=>1)
	)
)));
#pr($movies);
?>
<section class="home-highlights" id="main-slider">
	<? foreach($movies as $record):
	?>
		<div class="movie" style="background-image: url(<?= $record['Gallery'][0]['url']?>) ">
			<div class="col-container">
				<div class="movie-info-bg"></div>
				<div class="movie-info">
					<h3 class="title"><?= h($record['Movie']['title']) ?></h3>

					<?= $this->Text->truncate($record['Movie']['synopsis'],200)?>

					<ul class="features">
						<?php if($record['Movie']['director']){ ?>
						<li>
							<strong>Director(es):</strong> <?= $record['Movie']['director'] ?>
						</li>
						<?php
						}
						if($record['Movie']['genre']){
						?>
						<li>
							<strong>Género:</strong> <?= $record['Movie']['genre'] ?>
						</li>
						<?php
						}
						if($record['Movie']['duration']){
						?>
						<li>
							<strong>Duración:</strong> <?= $record['Movie']['duration'] ?> Min
						</li>
						<?php
						}
						if($record['Movie']['clasification']){
						?>
						<li>
							<strong>Clasificación:</strong> <?= $record['Movie']['clasification'] ?>
						</li>
						<?php
						}
						?>
					</ul>
					<?php
					if( $record['Movie']['trailer'] != '' ){
						echo $this->Html->link("[:ver_trailer:]",array('controller'=>'movies','action'=>'view','slug'=>$record['Movie']['slug'],"#"=>'trailer'),array('class'=>'see-trailer'));
					}else{
						echo $this->Html->link("[:mas_detalles:]",array('controller'=>'movies','action'=>'view','slug'=>$record['Movie']['slug']),array('class'=>'see-trailer'));
					}
					?>

				</div>
			</div>
		</div>
	<?php
	endforeach;
	?>



	<div class="col-container pagination-container">
		<div class="pagination">
			<ul>
				<?php
				for($i = 1; $i<=count($movies); $i++){
					echo $this->Html->tag("li",$this->Html->link($i,"#",array('class'=>$i == 1 ? "current":"")));
				}
				?>
			</ul>
		</div>
	</div>
</section>
