<!-- Map -->
<?php
if($map_provider !== "Google") {
	?>
	<script>
	<?php
	if($map_provider == 'Tomtom') {
		?>
		var mymap = new L.Map("map-canvas", map_provider_options).setView([<?= $default_latlng; ?>], 5);
		<?php
	}

	else {
		?>
		var mymap = L.map("map-canvas").setView([<?= $default_latlng; ?>], 5);

		L.tileLayer.provider("<?= $map_provider ?>", map_provider_options).addTo(mymap);
		<?php
	}
	?>

	// validate latlng field
	// first remove original
	$('#latlng').remove();

	// insert new one
	$('#map-wrapper').after('<input type="text" id="latlng" name="latlng" style="height:0;padding:0;opacity:0">');

	// set custom message
	var latlngInput = document.getElementById("latlng");
	latlngInput.setCustomValidity("<?= $txt_click_map  ?>");

	// marker
	var marker;

	mymap.on('click', function(e) {
		var latlngInput = document.getElementById("latlng");

		latlngInput.setCustomValidity("");

		if(marker) {
			mymap.removeLayer(marker);
		}

		marker = new L.marker(e.latlng).addTo(mymap);
		$("#latlng").val(e.latlng.lat + ", " + e.latlng.lng);
	});
	</script>
	<?php
}

else {
	?>
	<!-- Google Maps API -->
	<script src="https://maps.googleapis.com/maps/api/js?key=<?= $google_key; ?>"></script>
	<script>
	var map            = null;
	var marker         = null;
	var markers        = [];
	var update_timeout = null;
	var geocoder;

	// global infowindow object
	var infowindow = new google.maps.InfoWindow( {
		size: new google.maps.Size(150,50)
	});

	// default latitude and longitude
	var defaultLatLng = new google.maps.LatLng(<?= $default_latlng; ?>);

	// create the map
	var mapOptions = {
		zoom                  : 5,
		center                : defaultLatLng,
		mapTypeControl        : true,
		mapTypeControlOptions : {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		navigationControl     : true,
		mapTypeId             : google.maps.MapTypeId.ROADMAP
	}

	// init map
	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

	// init geocoder service
	geocoder = new google.maps.Geocoder();

	// when click on map, add marker
	google.maps.event.addListener(map, 'click', function(event){
		deleteMarkers();
		update_timeout = setTimeout(function(){
			if (marker) {
				marker.setMap(null);
				marker = null;
			}

			infowindow.close();

			marker = createMarker(event.latLng, "name", "<b><?= $txt_map_marker_location; ?></b><br>" + event.latLng);
			$("#latlng").val(event.latLng);
		}, 200);
	});

	// on double click
	google.maps.event.addListener(map, 'dblclick', function(event) {
		clearTimeout(update_timeout);
	});

	// address input on blur listener
	document.getElementById('address').addEventListener('blur', function(e){
		update_timeout = setTimeout(function(){
			if (marker) {
				marker.setMap(null);
				marker = null;
			}

			infowindow.close();

			codeAddress();
		}, 200);
	});

	// postal_code input on blur listener
	document.getElementById('postal_code').addEventListener('blur', function(e){
		update_timeout = setTimeout(function(){
			if (marker) {
				marker.setMap(null);
				marker = null;
			}

			infowindow.close();

			codeAddress();
		}, 200);
	});

	// A function to create the marker and set up the event window function
	function createMarker(latlng, name, html) {
		var contentString = html;
		var marker = new google.maps.Marker({
			position: latlng,
			map: map,
			zIndex: Math.round(latlng.lat()*-100000)<<5
			});

		google.maps.event.addListener(marker, 'click', function() {
			infowindow.setContent(contentString);
			infowindow.open(map,marker);
			});

		google.maps.event.trigger(marker, 'click');
		return marker;
	}

	// address input, on blur, set marker
	function codeAddress() {
		deleteMarkers();
		var address     = document.getElementById("address").value;
		var postal_code = document.getElementById("postal_code").value;
		var address_postal_code = address + ' ' + postal_code;
		geocoder.geocode( { 'address': address_postal_code}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				map.setZoom(12);
				var marker = new google.maps.Marker({
					map     : map,
					position: results[0].geometry.location
				});

				// add this marker to the array
				markers.push(marker);

				// update hiddent latlng input field
				$("#latlng").val(results[0].geometry.location);

			} else {
				console.log("Geocode was not successful for the following reason: " + status);
			}
		});
	}

	// Sets the map on all markers in the array.
	function setMapOnAll(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	}

	// Removes the markers from the map, but keeps them in the array.
	function clearMarkers() {
		setMapOnAll(null);
	}

	// Shows any markers currently in the array.
	function showMarkers() {
		setMapOnAll(map);
	}

	// Deletes all markers in the array by removing references to them.
	function deleteMarkers() {
		clearMarkers();
		markers = [];
	}
	</script>
	<!-- End Google Maps API -->
<?php
}
