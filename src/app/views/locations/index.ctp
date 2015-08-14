<section id="complex-container">
	<div id="map"></div>
	<div class="cities-location">
		<div class="cities col-container">
			<ul>
				<?php
				foreach( $cities as $id => $city ):
					?>
					<li>
						<a class="city-trigger <?= ( $id == $CitySelected['id'] ) ? 'selected' : '' ?>"
						   data-id="<?= $id ?>" href=""><?= $city ?></a>
					</li>
				<?php
				endforeach;
				?>
			</ul>
		</div>
	</div>

	<div class="complex-info col-container">
		<div class="complex">
			<div class="addresses">
				<?php
				foreach( $locations as $tmp_location ) :
					$location = $tmp_location['Location'];
					$services = $tmp_location['Service'];
					$gallery = $tmp_location['Gallery'];
					$city = $tmp_location['City'];
					if( count( $gallery ) ){
						$gallery = $gallery[0];
					}

					$selected_location = ( array_key_exists( $location['id'], $LocationsSelected ) ) ? true : false;
					?>

					<div class="address city-<?= $location['city_id'] ?> link" data-lat="<?= $location['mark_lat'] ?>"
					     data-lng="<?= $location['mark_lng'] ?>"
					     style="<?= ( $selected_location ) ? 'display: block' : '' ?>">
						<h2 class="title"><?= $location['name'] ?></h2>
						<div class="cover">
							<?php
							if( count( $gallery ) ){
								?>
								<img class="cover" src="<?= $gallery['medium'] ?>">
							<?php } ?>
							<?= $this->Html->link("[:mas-info:]",array('controller'=>'locations','action'=>'view','id'=>$location['id'],'slug'=>Inflector::slug($location['name'],"-")),array('class'=>'btn-primary fwd'));?>
						</div>

						<div class="text">
							<span class="subtitle">[:location_address:]</span>
							<p class="address-info">
								<?= $location['street']." #".$location['outside'].(!empty($location['interior'])? " - ".$location['interior'] : "")." ".$location['neighborhood']." C.P. ".$location['zip'].", ".$city['name'].", ".$location['state'] ?>
							</p>
							<span class="subtitle">[:location_phones:]</span>
							<?php
							$phones = explode( ',', $location['phone_numbers'] );
							foreach( $phones as $phone ):
								?>
								<span class="tel"><?= $phone ?></span>
							<?php endforeach;
							if(!empty($services)){
							?>
								<span class="subtitle">[:location_services:]</span>
								<ul class="services">
									<?php foreach( $services as $service ) : ?>
										<li class="service">
											<?= $this->Html->image($service['Icon']['url'],array('alt'=>$service['name']))?>
											<span class="serviceName"><?= h($service['name'])?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php
							}
							?>
						</div>
					</div>

				<?php endforeach; ?>

			</div>
		</div>
	</div>

</section>