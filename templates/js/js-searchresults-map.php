<!-- Map -->
<?php
if(!empty($results_arr)) {
	if($map_provider !== "Google") {
		?>
		<script>
		var resultsObj = <?php echo json_encode($results_arr); ?>;

		<!-- Set Map -->
		<?php
		if($map_provider == 'Tomtom') {
			?>
			var mymap = new L.Map("map-canvas", map_provider_options).setView([0.000, 0.000], 1);
			<?php
		}

		else {
			?>
			var mymap = L.map("map-canvas").setView([0.000, 0.000], 1);

			L.tileLayer.provider("<?= $map_provider ?>", map_provider_options).addTo(mymap);
			<?php
		}
		?>
		</script>

		<!-- Markers -->
		<script>
		// init marker array
		var markerArray = [];

		// set markers
		for (var k in resultsObj) {
			var p = resultsObj[k];

			L.marker([p.ad_lat, p.ad_lng])
			.bindPopup('<a href="' + '" target="_blank">' + p.ad_title + '</a>')
			.addTo(mymap);

			markerArray.push(L.marker([p.ad_lat, p.ad_lng]));
		} // end for (var k in resultsObj)

		var group = L.featureGroup(markerArray).addTo(mymap);
		mymap.fitBounds(group.getBounds());
		</script>

		<!-- events -->
		<script>
		$(".list-items .item").mouseover(function() {
			//marker = markers[this.getAttribute("data-ad_id")];

			var ad_id = this.getAttribute("data-ad_id");

			var result = resultsObj.filter(function( obj ) {
				return obj.ad_id == ad_id;
			});

			console.log(result[0].ad_title);
			tooltipPopup = L.popup({ offset: L.point(0, -20)});
			tooltipPopup.setContent(result[0].ad_title);
			tooltipPopup.setLatLng([result[0].ad_lat, result[0].ad_lng]);
			tooltipPopup.openOn(mymap);
		}); // end mouseover
		</script>
		<?php
	} // end if($map_provider !== "Google")

	if($map_provider == "Google") {
		?>
		<script>
		var results_obj = <?php echo json_encode($results_arr); ?>;
		var infowindow;
		var map;

		function initialize() {
			markers = {};
			infoboxcontents = {};

			// set map options
			var mapOptions = {
				zoom: 5,
				maxZoom: 15
			};

			// instantiate map
			var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			var bounds = new google.maps.LatLngBounds();
			var infowindow = new google.maps.InfoWindow();

			// $results_arr[] = array("ad_id" => $place_id, "ad_lat" => $ad_lat, "ad_lng" => $ad_lng, "ad_title" => $ad_title, "count" => $count);

			// set markers
			for (var k in results_obj) {
				var p = results_obj[k];
				var latlng = new google.maps.LatLng(p.ad_lat, p.ad_lng);
				bounds.extend(latlng);

				var marker_icon = '<?= $baseurl; ?>/imgs/marker1.png';

				// place markers
				var marker = new google.maps.Marker({
					position: latlng,
					map: map,
					animation: google.maps.Animation.DROP,
					title: p.ad_title,
					//icon: marker_icon
				});

				markers[p.ad_id] = marker;
				infoboxcontents[p.ad_id] = p.ad_title;

				// click event on markers to show infowindow
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.setContent(this.title);
					infowindow.open(map, this);
				});
			} // end for (var k in results_obj)

			map.fitBounds(bounds);

			$(".list-items .item").mouseover(function() {
				marker = markers[this.getAttribute("data-ad_id")];
				// mycontent = infoboxcontents[this.getAttribute("data-ad_id")];

				mycontent =  '<div class="scrollFix">' + infoboxcontents[this.getAttribute("data-ad_id")] + '</div>';
				// console.log(mycontent);

				infowindow.setContent(mycontent);
				// infowindow.setOptions({maxWidth:300});
				infowindow.open(map, marker);
				marker.setZIndex(10000);
			}); // end mouseover
		} //  end initialize()

		google.maps.event.addDomListener(window, 'load', initialize);
		</script>
		<?php
	} // end if($map_provider == "Google")
}
?>
