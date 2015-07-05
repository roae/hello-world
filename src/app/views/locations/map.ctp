<?php /* @var $this View */ ?>
<?php
echo $this->Html->div('canvas', "", array('id' => 'map'));
#echo $this->Html->div('view','',array('id' => 'street'));
$this->Html->script('//maps.google.com/maps/api/js?sensor=true', array('inline' => false));
?>
<script type="text/javascript">
	var map,markPosition,mapPosition;
	function initialize(){
		mapPosition = new google.maps.LatLng(<?= $this->params['named']['map_lat'] ?>,<?= $this->params['named']['map_lng'] ?>);
		markPosition = new google.maps.LatLng(<?= $this->params['named']['mark_lat'] ?>,<?= $this->params['named']['mark_lng'] ?>);

		map = new google.maps.Map($('#map')[0], {
			zoom: <?= $this->params['named']['map_zoom'] ? $this->params['named']['map_zoom']  : 12 ?>,
			center: mapPosition,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			streetViewControl: false,
			scrollwheel: false
		});


		var marker = new google.maps.Marker({
			map: map,
			position: markPosition
		});

		google.maps.event.addListener(map, 'load', function() {
			map.setCenter(markPosition);
		});

	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>