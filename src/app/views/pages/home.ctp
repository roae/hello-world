<?php /* @var $this View */?>
<?= $this->element("movies/home_slide");?>
<!--<section class="top-promo">
	  <a href="">¡Palomitas 2X1 los Martes y Jueves!</a>
</section>
-->

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
      <h2 class="titleBillboard">En cartelera <strong><?= isset($CitySelected['name']) ? $CitySelected['name'] : ""?></strong></h2>
       <?= $this->element("shows/billboard",array('cache' => array('key' => 'home_'.isset($CitySelected['name']) ? $CitySelected['name'] : "", 'time' => '+1 hour'))); ?>
    </div>
  </div>

  <section class="horizontal-banner">
   <?= $this->element("ads/show",array('type'=>'HORIZONTAL','cache' => array('key' => 'horizontal', 'time' => '+1 day'))); ?>
  </section>
</section>

<?= $this->element("movies/commingsoon",array('cache' => array('key' => isset($CitySelected['name']) ? $CitySelected['name'] : "", 'time' => '+1 hour'))); ?>

<?php
$this->I18n->addMissing("[:username-or-email:]","Label del campo username en el formulario de login","extras");
$this->I18n->addMissing("[:username-password:]","Label del campo password en el formulario de login","extras");
$this->I18n->addMissing("[:username-login:]","Boton del formulario de login","extras");
?>