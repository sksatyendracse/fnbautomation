<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php require_once( __DIR__ . '/favicon.inc.php') ?>

<!-- css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css">
<link rel="stylesheet" href="<?= $baseurl ?>/templates/css/styles.css">
<link rel="stylesheet" href="<?= $baseurl ?>/templates/icons/icons8/css/styles.min.css">

<!-- javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
<script src="<?= $baseurl ?>/lib/head.js"></script>

<!-- baseurl -->
<script>
var baseurl = '<?= $baseurl ?>';

// add CSRF token in the headers of all requests
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': '<?= session_id() ?>',
		'X-AJAX-Setup': 1
    }
});
</script>

<!-- geolocation -->
<?php
if(empty($_COOKIE['city_id'])) {
	?>
	<script>
	if ('geolocation' in navigator && !localStorage.clear_loc) {
		navigator.geolocation.getCurrentPosition(function(position) {
			var lat = position.coords.latitude;
			var lng = position.coords.longitude;
			var url = '<?= $baseurl ?>/inc/nearest-location.php';

			// debug
			console.log(lat + ', ' + lng);

			// ajax post
			$.post(url, {
				lat: lat,
				lng: lng
			}, function(data) {
				// parse json response
				data = JSON.parse(data);

				// create cookie
				createCookie('city_id', data.city_id, 90);

				// reload
				location.reload(true);
			});
		});
	}
	</script>
	<?php
}
?>

<?php
if(in_array(basename($_SERVER['SCRIPT_NAME']), array('place.php', 'list.php', '_searchresults.php'))) {
	include_once(__DIR__ . '/../inc/map-provider-options.php');
	?>
	<!-- CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css">
	<?php
	if($map_provider == 'Tomtom') {
		?>
		<link rel='stylesheet' type='text/css' href='<?= $baseurl ?>/lib/sdk-tomtom/map.css'/>
		<?php
	}
	?>

	<!-- Javascript -->
	<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>
	<script src="<?= $baseurl ?>/lib/leaflet-providers.js"></script>
	<script src="https://unpkg.com/leaflet.vectorgrid@latest/dist/Leaflet.VectorGrid.bundled.js"></script>

	<?php
	if($map_provider == 'Tomtom') {
		?>
		<script src="<?= $baseurl ?>/lib/sdk-tomtom/tomtom.min.js"></script>
		<?php
	}

	if($map_provider == 'Google') {
		?>
		<script src="https://maps.googleapis.com/maps/api/js?key=<?= $google_key ?>"></script>
		<?php
	}

}
