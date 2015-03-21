<section class="col-container" id="complex-container">
  <header class="top-message">
    <h1>Complejos</h1>
    <p>Lorem ipsum dolor sit amet</p>
  </header>

  <?php

  ?>

  <div class="complex-info">

    <div class="complex">

      <div id="map"></div>

      <div class="addresses">

        <?php
          foreach ($locations as $tmp_location) :
            $location = $tmp_location['Location'];
            $services = $tmp_location['Service'];
            $gallery = $tmp_location['Gallery'];

            if( count($gallery) ) {
              $gallery = $gallery[0];
            }

            $selected_location = ( array_key_exists($location['id'], $LocationsSelected) ) ? true : false;
        ?>

          <div class="address city-<?= $location['city_id'] ?>" data-lat="<?= $location['mark_lat'] ?>" data-lng="<?= $location['mark_lng'] ?>" style="<?= ($selected_location) ? 'display: block' : '' ?>">
            <strong class="title"><?= $location['name'] ?></strong>

            <?php
              if( count($gallery) )  {
            ?>

              <img class="cover" src="<?= $gallery['medium'] ?>">

            <?php } ?>

            <div class="text">
              <p class="address-info">
                <?= $location['street']." #".$location['outside']." ".$location['interior']." ".$location['neighborhood']." C.P. ".$location['zip'] ?>
              </p>

              <p class="description">
                <?= $location['description'] ?>
              </p>

              <?php
                $phones = explode(',', $location['phone_numbers']);
                foreach ($phones as $phone):
              ?>
                <span class="tel"><?= $phone ?></span>
              <?php endforeach; ?>

              <ul class="services">

                <?php foreach($services as $service) : ?>

                  <li class="service" style="background-image: url(<?= $service['Icon']['thumb'] ?>)"><?= $service['name'] ?></li>

                <?php endforeach; ?>
              </ul>
            </div>
          </div>

        <?php endforeach; ?>

      </div>
    </div>

    <aside>
      <ul>

        <?php
          foreach ($cities as $id => $city):
        ?>

          <li>
            <a class="city-trigger <?= ( $id == $CitySelected['id'] ) ? 'selected' : '' ?>" data-id="<?= $id ?>" href=""><?= $city ?></a>
          </li>

        <?php
          endforeach;
        ?>

      </ul>
    </aside>
  </div>

</section>