<?php /* @var $this View */ ?>
<?php
echo $this->Html->div('view','',array('id' => 'street'));
$this->Html->script('//maps.google.com/maps/api/js?sensor=true', array('inline' => false));
?>
<script type="text/javascript">
	var map,markPosition,mapPosition;
	function initialize(){
		panorama = new google.maps.StreetViewPanorama(document.getElementById("street"), {
			position: new google.maps.LatLng(<?= $this->params['named']['sv_lat']?>,<?= $this->params['named']['sv_lng'] ?>),
			addressControl:false,
			linksControl: true,
			navigationControlOptions: {
				style: google.maps.NavigationControlStyle.DEFAULT
			},
			enableCloseButton: false,
			visible:true,
			pov:{
				heading:<?= $this->params['named']['sv_heading'] ?>,
				pitch:<?= $this->params['named']['sv_pitch'] ?>,
				zoom:<?= $this->params['named']['sv_zoom'] ?>
			},
			scrollwheel: false
		});

	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>