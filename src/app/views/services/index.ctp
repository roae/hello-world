<div id="services-container">
  <header class="top-message">
    <h1>Servicios</h1>
    <p>Lorem ipsum dolor sit amet</p>
  </header>

  <div class="col-container">
    <ul class="services">

    <?php foreach ($recordset as $service) { ?>

      <li class="service"  style="background-image: url(<?= $service['Icon']['thumb'] ?>)">

      <?= $service['Service']['name'] ?>

      <?= $service['Service']['description'] ?>

        <div class="service-gallery" id="popup-gallery">

          <?php foreach ($service['Gallery'] as $picture) { ?>

            <a class="service-picture" href="<?= $picture['url'] ?>">
              <img src="<?= $picture['mini'] ?>">
            </a>

          <?php } ?>

        </div>
      </li>

      <?php } ?>

    </ul>

  </div>
</div>