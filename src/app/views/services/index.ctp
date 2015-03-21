<div id="services-container">
  <header class="top-message">
    <h1>Servicios</h1>
    <p>Lorem ipsum dolor sit amet</p>
  </header>

  <div class="col-container">
    <ul class="services">

    <?php foreach ($recordset as $service) { ?>

      <li class="service">

      <?= $service['Service']['name'] ?>

        <ul class="service-gallery">
          <li class="service-picture">
            <a href="http://placehold.it/1024x480">
              <img src="http://placehold.it/64x64">
            </a>
          </li>
          <li class="service-picture">
            <a href="http://placehold.it/1024x480">
              <img src="http://placehold.it/64x64">
            </a>
          </li>
          <li class="service-picture">
            <a href="http://placehold.it/1024x480">
              <img src="http://placehold.it/64x64">
            </a>
          </li>
          <li class="service-picture">
            <a href="http://placehold.it/1024x480">
              <img src="http://placehold.it/64x64">
            </a>
          </li>
        </ul>
      </li>

      <?php } ?>

    </ul>

  </div>
</div>