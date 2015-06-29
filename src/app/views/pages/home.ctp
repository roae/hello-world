<?= $this->element("movies/home_slide");?>
<section class="top-promo">
  <a href="">¡Palomitas 2X1 los Martes y Jueves!</a>
</section>

<section class="billboard">
  <div class="col-container">

    <header>
      <h1>[:tag-line:]</h1>
    </header>

    <div class="description">
      [:tag-line-description:]
    </div>

    <?php //$this->element("locations/select");?>
    <div class="movies">
      <h2 class="titleBillboard">En cartelera <strong><?php Configure::read("LocationSelected.name")?></strong></h2>
       <?= $this->element("shows/billboard"); ?>
    </div>
  </div>

  <section class="horizontal-banner">
   <?= $this->element("ads/show",array('type'=>'HORIZONTAL'));?>
  </section>
</section>

<?= $this->element("movies/commingsoon"); ?>