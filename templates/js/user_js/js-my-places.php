<script type="text/javascript">
// remove place modal
$('#remove-place-modal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget); // Button that triggered the modal
	var place_id = button.data('place-id'); // Extract info from data-* attributes
	var modal = $(this);

	// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	var post_url = '<?= $baseurl; ?>' + '/user/ajax-get-place-name.php';

	$.post(post_url, { place_id: place_id },
		function(data) {
			modal.find('.modal-title').text(data);
			modal.find('.remove-place').data('remove-id', place_id);
		}
	);
});

// remove place submit
$(document).ready(function(){
	$('.remove-place').click(function(e) {
		e.preventDefault();
		var place_id = $(this).data('remove-id');
		var post_url = '<?= $baseurl; ?>' + '/user/process-remove-place.php';
		var wrapper = '#place-' + place_id;
		$.post(post_url, {
			place_id: place_id
			},
			function(data) {
				if(data) {
					$(wrapper).empty();
					var place_removed = $('<div class="alert alert-success"></div>');
					$(place_removed).text(data);
					$(place_removed).hide().appendTo(wrapper).fadeIn();
				}
			}
		);
	});

});

</script>