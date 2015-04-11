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

        <ul class="service-gallery">

          <?php foreach ($service['Gallery'] as $picture) { ?>

          <li class="service-picture">
            <a href="http://placehold.it/1024x480">
              <img src="http://placehold.it/64x64">
            </a>
          </li>

          <?php } ?>

        </ul>
      </li>

      <?php } ?>

    </ul>

  </div>
</div>