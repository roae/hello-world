<?php
  $cities = $this->requestAction(array(
    'controller'=>'cities',
    'action'=>'get',
    'type'=>'list',
    'query'=>array(
      'conditions'=>array(
        'City.trash'=>0,
        'City.status'=>1,
      )
    )
  ));
  #pr($cities);
  #pr(Configure::read("CitySelected"));

  $places_label = 'Selecciona tu ciudad';
  $places_url = '#';
  if( isset($CitySelected['name']) && $CitySelected['name'] ) {
    $places_label = 'Ver cartelera de <span class="current">'.$CitySelected['name'].'</span>';
    $places_url = array('controller' => 'shows', 'action' => 'index', 'slug' => Inflector::slug(low($CitySelected['name']), '-'));
  }
?>

<div id="header-location-select">

  <?= $this->Html->link($places_label, $places_url, array('escape' => false)); ?>

  <div class="sub-menu-container">
    <span class="sub-menu-trigger">Open</span>

    <ul class="places">

      <?php foreach ($cities as $key => $value) { ?>

        <li>
          <?= $this->Html->link($value, array('controller' => 'shows', 'action' => 'index', 'slug' => Inflector::slug(low($value), '-'))) ?>
        </li>

      <?php } ?>

    </ul>
  </div>

</div>