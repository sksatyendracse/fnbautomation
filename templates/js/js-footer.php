<?php
if($cfg_use_select2) {
	?>
	<!-- city selector in navbar -->
	<script>
	$('#city-input').select2({
		ajax: {
			url: '<?= $baseurl; ?>/_return_cities_select2.php',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					query: params.term,
					page: params.page
				};
			}
		},
		escapeMarkup: function (markup) { return markup; },
		minimumInputLength: 1,
		dropdownAutoWidth : true,
		placeholder: "<?= $txt_inputplaceholder_city; ?>",
		allowClear: true,
		language: "<?= $html_lang; ?>"
	});

	// change x mark
	$(document).ready(function(){
		$('.select2-selection__clear').empty().html('<small class="text-muted"><i class="fa fa-minus-square-o" aria-hidden="true"></i></small>');
	});
	</script>
<?php
}
?>

<!-- preselect city in search bar -->
<?php
if(!empty($_COOKIE['city_id'])) {

	$option_text = (!empty($_COOKIE['city_name'])) ? $_COOKIE['city_name'] : '';
	$option_text .= (!empty($_COOKIE['state_abbr'])) ? ', ' . $_COOKIE['state_abbr'] : '';
	?>
	<script>
	// create the option and append to Select2
	var option = new Option('<?= $option_text ?>', '<?= $_COOKIE['city_id'] ?>', true, true);

	$('#city-input').append(option).trigger('change');

	<?php
	if($cfg_use_select2) {
		?>
		// manually trigger the `select2:select` event
		$('#city-input').trigger({
			type: 'select2:select',
			params: {
				city_id: <?= $_COOKIE['city_id'] ?>
			}
		});
		<?php
	}
	?>
	</script>
	<?php
}
?>

<!-- location modal -->
<script>
<?php
if($cfg_use_select2) {
	?>
	$('#city-change').select2({
		ajax: {
			url: '<?= $baseurl; ?>' + '/_return_cities_select2.php',
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
		minimumInputLength: 1,
		dropdownAutoWidth : true,
		placeholder: "<?= $txt_inputplaceholder_city; ?>",
		language: "<?= $html_lang; ?>"
	});
	<?php
}
?>

$(document.body).on('change', '#city-change', function(){
	delete_cookie('city_name');
	createCookie('city_id', this.value, 90);

	<?php
	if(basename($_SERVER['SCRIPT_NAME']) == 'list.php') {
		// get category
		if(!empty($cat_id)) {
			?>
			window.location.replace('<?= $baseurl ?>/city/list/cat/c-' + this.value + '-<?= $cat_id ?>-1');
			<?php
		}

		else {
			?>
			location.reload(true);
			<?php
		}
	}

	else {
		?>
		location.reload(true);
		<?php
	}
	?>
});

// insert html for use current location link
if ('geolocation' in navigator && localStorage.clear_loc) {
	var current_location_link = '<br><a href="#"><?= $txt_current_location ?></a>';

	$('#current-location-link').append(current_location_link);

	$(document.body).on('click', '#current-location-link a', function() {
		localStorage.removeItem('clear_loc');
		location.reload(true);
	});
}
</script>

<!-- clear location -->
<script>
$(document.body).on('click', '#clear-city', function(e){
	e.preventDefault();
	delete_cookie('city_id');
	delete_cookie('city_name');
	localStorage.setItem('clear_loc', 1);
	location.reload(true);
});
</script>

<!-- custom functions -->
<script>
function createCookie(name, value, days) {
    var expires;
    var cookie_path;
	var path = "<?= $install_path; ?>";

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    } else {
        expires = "";
    }

	if (path != '') {
		cookie_path = "; path=" + path;
	} else {
		cookie_path = "";
	}

    document.cookie = name + "=" + value + expires + cookie_path;
}

function delete_cookie(name) {
	createCookie(name, "", -100);
}
</script>