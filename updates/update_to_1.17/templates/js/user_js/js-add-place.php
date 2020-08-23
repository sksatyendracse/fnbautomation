<!-- Map -->
<?php
if($map_provider !== "Google") {
	?>
	<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
	<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

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

		var geocoder = L.Control.geocoder({
				defaultMarkGeocode: false
			})
			.on('markgeocode', function(e) {
				var bbox = e.geocode.bbox;
				var poly = L.polygon([
				bbox.getSouthEast(),
				bbox.getNorthEast(),
				bbox.getNorthWest(),
				bbox.getSouthWest()
				]);

				mymap.fitBounds(poly.getBounds());
			}).addTo(mymap);
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
?>

<!-- Ajax upload -->
<script src="<?= $baseurl; ?>/lib/ajaxupload/ajaxupload.js"></script>
<script>
new AjaxUpload('upload-button', {
	action      : '<?= $baseurl; ?>/user/process-upload.php',
	name        : 'userfile',
	data        : {},
	autoSubmit  : true,
	responseType: false,
	onChange    : function(file, extension){},
	onSubmit    : function(file, ext) {
		// Allow only images. You should add security check on the server-side.
		// Add preloader
		if (ext && /^(jpg|png|jpeg|gif)$/i.test(ext)) {
			$('<div class="thumbs-preloader" id="preloader"><i class="fa fa-spinner fa-spin"></i></div>').appendTo('#uploaded');
			// count number of div.thumbs
			var index = $('.thumbs').length;

			// add 1 to index on submit upload
			index = index + 1;

			// disable upload button after max upload limit is reached
			if(index == <?= $max_pics; ?>) {
				$('#upload_button').text('Limit');
				this.disable();
			}
		}
		else {
			// extension is not allowed
			$('<div id="upload_failed"></div>').appendTo('#uploaded').text('Invalid file type');

			// cancel upload
			return false;
		}
	},
	onComplete: function(file, response) { // response echoed from  process-upload.php
		// closure
		Uploader = this;

		// debug
		console.log(response);

		// check if previous upload failed because of non allowed ext
		// #upload_failed div created by onSumit function above
		if ($('#upload_failed').length) {
			$('#upload_failed').fadeOut("fast", function() { $(this).remove(); });
		}

		// delete preloader spinner
		$('#preloader').fadeOut("fast", function() { $(this).remove(); });

		if(response == 1) {
			// Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
			$('<div id="upload_failed"></div>').appendTo('#uploaded').text('<?= $txt_error_file_size; ?>');
			// cancel upload
			return false;
		}

		else if(response == 10) {
			// Value: 10; custom error code, failed to move file
			$('<div id="upload_failed"></div>').appendTo('#uploaded').text('<?= $txt_error_upload; ?>');
			// cancel upload
			return false;
		}

		else if(response == 11) {
			// Value: 11; custom error code, no submit token
			$('<div id="upload_failed"></div>').appendTo('#uploaded').text('<?= $txt_error_upload; ?>');
			// cancel upload
			return false;
		}

		else if(response == 12) {
			// Value: 12; custom error code, more than max num pics
			$('<div id="upload_failed"></div>').appendTo('#uploaded').text('Error: number of uploads exceeded (max <?= $max_pics; ?>)');
			// cancel upload
			return false;
		}

		else {  // upload success
			var thumb = '<?= $pic_baseurl; ?>/<?= $place_tmp_folder ?>/' + response;

			// store thumb container div in memory
			var temp_thumb_div = $('<div class="thumbs"></div>');

			// display uploaded pic's thumb
			$('<img />').attr('src', thumb).attr('width', '132').appendTo(temp_thumb_div);
			$('<div class="btn-delete-thumb delete_pic"><i class="fa fa-times-circle-o"></i></div>').appendTo(temp_thumb_div);
			$('<input type="hidden" name="uploads[]" />').attr('value', response).appendTo(temp_thumb_div);
			$('#uploaded').append(temp_thumb_div);

			// unbind click event to previous .delete_pic links and attach again so that the click event is not assigned twice to the same .delete_pic link
			$('.delete_pic').unbind('click');

			// make delete link work
			$('.delete_pic').click(function() {
				// get pic filename from hidden input
				var pic = $(this).next().attr('value');

				// remove div.thumbs
				$(this).parent().fadeOut("fast", function() { $(this).remove(); });

				//
				$('<input type="hidden" name="delete_temp_pics[]" />').attr('value', pic).appendTo('#uploaded');

				// re-enable upload button
				$('#upload_button').text('<?= $txt_form_upload_button; ?>');
				Uploader.enable();
			});
		} // end else
	} // end onComplete
});
</script>

<!-- Select2 Library -->
<script>
<?php
if($cfg_use_select2) {
	?>
	// initialize Select2 on the <select> element that you want to make awesome.
	$('#category_id-removed').select2({
		placeholder: '<?= $txt_placeholder_select_cat; ?>'
	});

	$('#city_id').select2({
		ajax: {
			url: '<?= $baseurl; ?>/_return_cities_select2.php',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					query: params.term, // search term
					page: params.page
				};
			}
		},
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 1
	});
	<?php
}
?>
</script>

<!-- Hours Widget -->
<script>
$('#btn-add-hours').click(function() {
	// get values from select fields
	var hours_weekday     = $('#hours-weekday').val();
	var hours_weekday_txt = $('#hours-weekday option:selected').text();
	var hours_start       = $('#hours-start').val();
	var hours_start_txt   = $('#hours-start option:selected').text();
	var hours_end         = $('#hours-end').val();
	var hours_end_txt     = $('#hours-end option:selected').text();
	var hours             = hours_weekday + ',' + hours_start + ',' + hours_end;

	var div_row = '<div class="hours-row"><span class="weekday"><strong>';
	div_row += hours_weekday_txt;
	div_row += '</strong></span> <span class="start">';
	div_row += hours_start_txt;
	div_row += '</span><span>-</span><span class="end">';
	div_row += hours_end_txt;
	div_row += '</span><a class="remove-hours"><i class="fa fa-times"></i></a>';
	div_row += '<input type="hidden" name="business_hours[]" value="' + hours + '"></div>';
	$(div_row).appendTo('#selected-hours');

	$('.remove-hours').click(function() {
		$(this).parent().fadeOut("fast", function() { $(this).remove(); });
	});
}); // end #btn-add-hours click

$('.remove-hours').click(function() {
	$(this).parent().fadeOut("fast", function() { $(this).remove(); });
});
</script>

<!-- Delete Pics -->
<script>
	$('.delete_existing_pic').click(function() {
		// get pic filename from hidden input
		var pic = $(this).next().attr('value');

		// remove div.thumbs
		$(this).parent().fadeOut("fast", function() { $(this).remove(); });

		//
		$('<input type="hidden" name="delete_existing_pics[]" />').attr('value', pic).appendTo('#uploaded');

		// re-enable upload button
		$('#upload_button').text('<?= $txt_form_upload_button; ?>');

		Uploader.enable();
	});
</script>

<!-- Form Submit -->
<script>
$("#the_form").submit(function() {

});
</script>

<!-- Tooltips -->
<script>
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})
</script>

<!-- Bootstrap modifications -->
<script>
// Allow Bootstrap dropdown menus to have forms/checkboxes inside,
// and when clicking on a dropdown item, the menu doesn't disappear.
$(document).on('click', '.dropdown-menu.dropdown-menu-form', function(e) {
	e.stopPropagation();
});
</script>
