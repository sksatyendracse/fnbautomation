<script>
$(document).ready(function() {
	// generate a click on the hidden input file field
	$('#upload-coupon-img').click(function(e){
		e.preventDefault();
		$('#coupon_img').click();
	});

	$("#coupon_img").on('change',(function(e) {
		// append file input to form data
		var fileInput = document.getElementById('coupon_img');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('coupon_img', file);

		$.ajax({
			url: "process-upload-coupon.php",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend : function() {
				// Add preloader
				$('<div class="thumbs-preloader" id="coupon-preloader"><i class="fa fa-spinner fa-spin"></i></div>').appendTo('#coupon-img');
			},
			success: function(data) {
				// parse json string
				var data = JSON.parse(data);

				// check if previous upload failed because of non allowed ext
				// #upload_failed div created by onSumit function above
				if ($('#upload-failed').length){
					$('#upload-failed').remove();
				}

				// delete preloader spinner
				$('#coupon-preloader').remove();

				// remove current profile pic
				$('#coupon-img').empty();

				if(data.result == 'success') {

					// create thumbnail src
					var coupon_img = '<img src="' + data.message + '" width="<?= $coupon_size[0] ?>">';

					// display uploaded pic's thumb
					$('#coupon-img').append(coupon_img);

					// add hidden input field
					$('#uploaded_img').val(data.filename);

				}

				else {
					$('<div id="upload-failed"></div>').appendTo('#coupon-img').text(data.message);
				}
			},
			error: function(e) {
					$('<div id="upload-failed"></div>').appendTo('#coupon-img').text(e);
			}
		});
	}));
});
</script>