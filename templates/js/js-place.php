<!-- raty (rating library) -->
<script>
$.fn.raty.defaults.path = '<?= $baseurl; ?>/templates/lib/raty/images';
$('.item-rating').raty({
	readOnly: true,
	score: function() {
		return this.getAttribute('data-rating');
	}
});

$('.review-rating').raty({
	readOnly: true,
	score: function() {
		return this.getAttribute('data-rating');
	}
});

$('.raty').raty({
	scoreName: 'review_score',
	target : '#hint',
	targetKeep : true,
	hints: ['<?= $txt_raty_bad; ?>', '<?= $txt_raty_poor; ?>', '<?= $txt_raty_regular; ?>', '<?= $txt_raty_good; ?>', '<?= $txt_raty_gorgeous; ?>']
});
</script>

<!-- lightbox -->
<link rel="stylesheet" href="<?= $baseurl; ?>/templates/lib/lightbox-master/dist/ekko-lightbox.min.css">
<script src="<?= $baseurl; ?>/templates/lib/lightbox-master/dist/ekko-lightbox.min.js"></script>
<script>
$(document).ready(function(){
	// lightbox
	$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox();
	});
});
</script>

<!-- submit review form -->
<script>
$(document).ready(function(){
	$('#submit-review').click(function() {
		var place_id        = $('#place_id').val();
		var place_name      = $('#place_name').val();
		var place_slug      = $('#place_slug').val();
		var place_city_slug = $('#place_city_slug').val();
		var place_city_id   = $('#place_city_id').val();
		var review_score    = $('input[name=review_score]').val();
		var review          = $('#review').val();
		var url             = '<?= $baseurl; ?>/process-review.php';

		// ajax post
		$.post(url, {
			place_id:        place_id,
			place_name:      place_name,
			place_slug:      place_slug,
			place_city_slug: place_city_slug,
			place_city_id:   place_city_id,
			review_score:    review_score,
			review:          review
		}, function(data) {
			$('#review-form').fadeOut();
			// alert(data);
			var form_wrapper = $('#review-form-wrapper');
			var alert_response = $('<div class="alert alert-success"></div>');
			$(alert_response).text(data);
			$(alert_response).hide().appendTo(form_wrapper).fadeIn();
		});
	});
});
</script>

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
?>

<!-- contact form -->
<script>
$(document).ready(function(){
	$('#contact-owner-result').hide();

	$('#submit-contact-owner').click(function(e) {
		e.preventDefault();

		// vars
		var place_id      = $('#place_id').val();
		console.log('place id is ' + place_id);
		var sender_email  = $('#sender_email').val();
		var sender_msg    = $('#sender_msg').val();
		var verify_answer = $('#verify_answer').val();
		var url           = '<?= $baseurl; ?>/plugins/contact_owner/send-msg.php';
		var spinner       = '<i class="fa fa-spinner fa-spin fa-fw"></i><span class="sr-only"><?= $txt_loading; ?></span>';

		// hide form and show spinner
		$('#contact-owner-result').show();
		$('#contact-owner-form').toggle(120);
		$('#contact-owner-result').html(spinner).fadeIn();

		// ajax post
		$.post(url, {
			place_id      : place_id,
			sender_email  : sender_email,
			sender_msg    : sender_msg,
			verify_answer : verify_answer
		}, function(data) {

			$('#contact-owner-result').html(data).fadeIn();

			// bind event that shows form when alert is dismissed
			$('.contact-owner-alert').bind('closed.bs.alert', function () {
				$('#contact-owner-form').toggle(120);
			});
		});
	});


});
</script>