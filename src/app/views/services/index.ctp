<?php /* @var $this View */ ?>
<div id="services-container">
	<div class="top-message">
		<h1>[:Services_title:]</h1>
		[:services_text:]
	</div>

	<div class="col-container">
		<ul class="services">

			<?php foreach( $recordset as $service ){ ?>

				<li class="service">
					<figure>
						<?= $this->Html->image($service['Icon']['url'],array('alt'=>$service['Service']['name'])); ?>
					</figure>
					<div class="content">
						<h2><?= h($service['Service']['name']) ?></h2>

						<?= $this->Xhtml->para("description",h($service['Service']['description'])); ?>

						<div class="service-gallery" >

							<?php foreach( $service['Gallery'] as $picture ){ ?>

								<a class="service-picture litebox" data-litebox-group="group-<?= $service['Service']['id'] ?>" href="<?= $picture['url'] ?>">
									<img src="<?= $picture['mini'] ?>">
								</a>

							<?php } ?>

						</div>
					</div>
				</li>

			<?php } ?>

		</ul>

	</div>
</div>
<?= $this->element("movies/commingsoon",array('cache' => array('key' => isset($CitySelected['name']) ? $CitySelected['name'] : "", 'time' => '+1 hour'))); ?>
<?php
$this->Html->script( array(
	'ext/images-loaded.min.js',
	'ext/litebox.min.js',
), false );
?>