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

						<div class="service-gallery" id="popup-gallery">

							<?php foreach( $service['Gallery'] as $picture ){ ?>

								<a class="service-picture" href="<?= $picture['url'] ?>">
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