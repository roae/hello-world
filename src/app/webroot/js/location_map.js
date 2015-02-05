var geocoder;
var map;
var sv = new google.maps.StreetViewService();
var panorama;
var marker=false;
var address;
function initialize() {
	geocoder = new google.maps.Geocoder();
	var lat=($("#LocationMapLat").val() == "") ? 23.231458238957803 : parseFloat($("#LocationMapLat").val());
	var lng=($("#LocationMapLng").val() == "") ? -106.41798364537046 : parseFloat($("#LocationMapLng").val());
	var latlng = new google.maps.LatLng(lat,lng);

	var markLat=($("#LocationMarkLat").val() == "") ? 23.231458238957803 : parseFloat($("#LocationMarkLat").val());
	var markLng=($("#LocationMarkLng").val() == "") ? -106.41798364537046 : parseFloat($("#LocationMarkLng").val());
	var markLatlng = new google.maps.LatLng(markLat,markLng);

	var svLat=($("#LocationSvLat").val() == "") ? 23.231458238957803 : parseFloat($("#LocationSvLat").val());
	var svLng=($("#LocationSvLng").val() == "") ? -106.41798364537046 : parseFloat($("#LocationSvLng").val());
	var svLatlng = new google.maps.LatLng(svLat,svLng);

	var myOptions = {
		zoom: ($("#LocationMapZoom").val() == "") ? 12 : parseFloat($("#LocationMapZoom").val()),
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false
	};

	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	if($("#LocationMarkLat").val() != ""){
		marker = new google.maps.Marker({
			map: map,
			position: markLatlng,
			title:$("#LocationDireccion").val(),
			draggable:true
		});

		google.maps.event.addListener(marker, "drag", function() {
			$("#LocationMarkLat").val(marker.getPosition().lat());
			$("#LocationMarkLng").val(marker.getPosition().lng());
		});
	}

	google.maps.event.addListener(map, 'center_changed', function() {
		$("#LocationMapLat").val(map.getCenter().lat());
		$("#LocationMapLng").val(map.getCenter().lng());
	});

	google.maps.event.addListener(map,'zoom_changed',function(){
		$("#LocationMapZoom").val(map.getZoom());
	});

	var panoOptions = {
		position: svLatlng,
		addressControl:false,
		linksControl: true,
		navigationControlOptions: {
			style: google.maps.NavigationControlStyle.DEFAULT
		},
		enableCloseButton: false,
		visible:true,
		pov:{
			heading:parseFloat(($("#LocationSvHeading").val() != "" ) ? parseFloat($("#LocationSvHeading").val()) : 0),
			pitch:parseFloat(($("#LocationSvPitch").val() != "" ) ? parseFloat($("#LocationSvPitch").val()) : 0),
			zoom:parseFloat(($("#LocationSvZoom").val() != "" ) ? parseFloat($("#LocationSvZoom").val()) : 1),
		}
	};

	panorama = new google.maps.StreetViewPanorama(document.getElementById("streetView"), panoOptions);

	google.maps.event.addListener(panorama,"pov_changed",function(){
		$("#LocationSvHeading").val(panorama.getPov().heading);
		$("#LocationSvPitch").val(panorama.getPov().pitch);
		$("#LocationSvZoom").val(panorama.getPov().zoom);
	});

	google.maps.event.addListener(panorama,"position_changed",function(){
		$("#LocationSvLat").val(panorama.getPosition().lat());
		$("#LocationSvLng").val(panorama.getPosition().lng());
	});

}
$(function(){
	initialize();
	$("#LocationDireccion").bind('focus',function(){
		address=$(this).val();
	});
	$("input, select",$("#LocationStreet" ).closest("fieldset")).bind('blur',function(){
		sync_direct();
	});

	$("#sincronizar").click(function(){
		panorama.setPosition(marker.getPosition());
	});

	$("#sinc_direct").click(sync_direct);

	function getAddress(){
		return $("#LocationStreet" ).val()+" "+$("#LocationInterior" ).val()+($("#LocationOutside" ).val() != undefined ? "  "+$("#LocationOutside" ).val(): "")+" "+$("#LocationNeighborhood" ).val()+" "+Cities[$("#LocationCityId" ).val()]+", "+$("#LocationState" ).val()+" "+$("#LocationZip" ).val()
	}

	function sync_direct(){
		var _address = getAddress();
		console.log(_address);
		if(address!=_address){
			address = _address;
			if (geocoder) {
				geocoder.geocode( {
					'address': address
				}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						map.setCenter(results[0].geometry.location);
						map.setZoom(15);
						if(marker){
							marker.setPosition(results[0].geometry.location);
							marker.setTitle(address);
						}else{
							marker = new google.maps.Marker({
								map: map,
								position: results[0].geometry.location,
								title: address,
								draggable:true
							});
							google.maps.event.addListener(marker, "drag", function() {
								$("#LocationMarkLat").val(marker.getPosition().lat());
								$("#LocationMarkLng").val(marker.getPosition().lng());
							});
						}
						//console.dir(marker);
						panorama.setPosition(results[0].geometry.location)
						panorama.setVisible(true);

						$("#LocationMapLat").val(map.getCenter().lat());
						$("#LocationMapLng").val(map.getCenter().lng());
						$("#LocationMarkLat").val(marker.getPosition().lat());
						$("#LocationMarkLng").val(marker.getPosition().lng());

					} else {
						alert("No se encontró la dirección en google maps. Error: " + status);
					}
				});
			}
		}
		return false;
	}
});