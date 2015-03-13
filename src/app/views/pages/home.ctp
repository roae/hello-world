<link rel="stylesheet" href="/css/owl.carousel.css">
<link rel="stylesheet" href="/css/owl.theme.css">

<section class="home-highlights" id="main-slider">
  <div class="movie big-hero">
    <div class="col-container">
      <div class="movie-info-bg"></div>
      <div class="movie-info">
        <h3 class="title">Big Hero 6</h3>

        <p>
          Una comedia de aventuras y acción sobre un experto en robótica llamado Hiro Hamada, quien se encuentra dentro de un complot criminal que amenaza con ...
        </p>

        <ul class="features">
          <li>
            <strong>Director(es):</strong> Don Hall, Chris William
          </li>
          <li>
            <strong>Género:</strong> Animación
          </li>
          <li>
            <strong>Duración:</strong> 109min
          </li>
          <li>
            <strong>Clasificación:</strong> A
          </li>
        </ul>

        <a class="see-trailer" href="">Ver trailer</a>
      </div>
    </div>
  </div>

  <div class="movie birdman">
    <div class="col-container">
      <div class="movie-info-bg"></div>
      <div class="movie-info">
        <h3 class="title">Birdman</h3>

        <p>
        Un actor (Keaton), famoso por interpretar a un superhéroe icónico, lucha para montar una obra de Broadway. En los días previos a la noche de apertura, se enfrenta ...
        </p>

        <ul class="features">
          <li>
            <strong>Director(es):</strong> Alejandro Gonzalez Iñárritu
          </li>
          <li>
            <strong>Género:</strong> Comedia
          </li>
          <li>
            <strong>Duración:</strong> 112min
          </li>
          <li>
            <strong>Clasificación:</strong> C
          </li>
        </ul>

        <a class="see-trailer" href="">Ver trailer</a>
      </div>
    </div>
  </div>

  <div class="movie escobar">
    <div class="col-container">
      <div class="movie-info-bg"></div>
      <div class="movie-info">
        <h3 class="title">Escobar</h3>

        <p>
          Andrea Di Stefano dirige esta co-producción franco-belga-española en la que Josh Hutcherson ('Los Juegos del Hambre') interperta a Nick, un joven surfero ...
        </p>

        <ul class="features">
          <li>
            <strong>Director(es):</strong> Andrea Di Stefano
          </li>
          <li>
            <strong>Género:</strong> Drama
          </li>
          <li>
            <strong>Duración:</strong> 98min
          </li>
          <li>
            <strong>Clasificación:</strong> C
          </li>
        </ul>

        <a class="see-trailer" href="">Ver trailer</a>
      </div>
    </div>
  </div>

  <div class="col-container pagination-container">
    <div class="pagination">
      <ul>
        <li>
          <a class="current" href="">1</a>
        </li>

        <li>
          <a href="">2</a>
        </li>

        <li>
          <a href="">3</a>
        </li>
      </ul>
    </div>
  </div>
</section>

<section class="top-promo">
  <a href="">¡Palomitas 2X1 los Martes y Jueves!</a>
</section>

<section class="billboard">
  <div class="col-container">

    <header>
      <h1>¡Un mundo de diversión!</h1>
    </header>

    <p class="description">
      Consulta nuestra cartelera y no te pierdas de nuestros próximos estrenos, tenemos promociones y descuentos.
    </p>

    <?php $this->element("locations/select");?>
    <div class="movies">
      <h2 class="titleBillboard">En cartelera <strong><?php Configure::read("LocationSelected.name")?></strong></h2>

      <ul class="movies-list">
        <?= $this->element("shows/billboard"); ?>
      </ul>
    </div>
  </div>

  <section class="middle-promo">
    <?= $this->Html->image("refill.png",array('alt'=>'[:logo_alt:]')) ?>
  </section>
</section>

<?= $this->element("movies/commingsoon"); ?>