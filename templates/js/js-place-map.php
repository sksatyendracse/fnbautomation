<!-- set map -->
<?php
if($lat != '0.00000000') {
	if($map_provider !== "Google") {
		?>
		<script>
		/*--------------------------------------------------
		SET MAP
		--------------------------------------------------*/
		<?php
		if($map_provider == 'Tomtom') {
			?>
			var mymap = new L.Map("place-map-canvas", map_provider_options).setView([<?= $lat; ?>, <?= $lng; ?>], 13);
			<?php
		}

		else {
			?>
			var mymap = L.map("place-map-canvas").setView([<?= $lat; ?>, <?= $lng; ?>], 13);

			L.tileLayer.provider("<?= $map_provider ?>", map_provider_options).addTo(mymap);
			<?php
		}
		?>

		// set marker
		var marker = L.marker([<?= $lat; ?>, <?= $lng; ?>]).addTo(mymap);
		</script>
		<?php
	} // end if($map_provider !== "Google")

	if($map_provider == "Google") {
		?>
		<script>
		var myLatlng = new google.maps.LatLng(<?= $lat; ?>, <?= $lng; ?>);
		var mapOptions = {
		  zoom: 12,
		  center: myLatlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		}
		var map = new google.maps.Map(document.getElementById("place-map-canvas"), mapOptions);

		var marker = new google.maps.Marker({
			position: myLatlng,
			title:""
		});

		// To add the marker to the map, call setMap();
		marker.setMap(map);
		</script>
	<?php
	}
}
else {
	?>
	<script>
	$('#place-map-wrapper').hide();
	</script>
	<?php
}
