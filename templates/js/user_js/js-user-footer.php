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
		language: "<?= $html_lang; ?>"
	});
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
	location.reload(true);
});

$(document.body).on('click', '#clear-city', function(e){
	e.preventDefault();
	delete_cookie('city_id');
	delete_cookie('city_name');
	location.reload(true);
});

/* CUSTOM FUNCTIONS */
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
