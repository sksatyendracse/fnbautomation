<script src="<?= $baseurl; ?>/lib/ajaxupload/ajaxupload.js"></script>
<script>
$(document).ready(function() {
	// pic uploader
	var upload = new AjaxUpload('upload-profile-pic', {
		action: 'process-upload-profile.php',
		name: 'userfile',
		// data: {userid: },
		autoSubmit: true,
		responseType: false,
		onChange: function(file, extension){},
		onSubmit: function(file, ext) {
			// Allow only images. You should add security check on the server-side.
			if (ext && /^(jpg|png|jpeg|gif)$/i.test(ext)) {
				// Add preloader
				$('<div class="thumbs-preloader" id="preloader"><i class="fa fa-spinner fa-spin"></i></div>').appendTo('#profile-pic');
			}
			else {
				// extension is not allowed
				$('<div id="upload-failed"></div>').appendTo('#profile-pic').text('Invalid file type');
				// cancel upload
				return false;
			}
		}, // end onSubmit
		onComplete: function(file, response) { // response contains uploaded image's filename
			// closure
			Uploader = this;

			// check if previous upload failed because of non allowed ext
			// #upload_failed div created by onSumit function above
			if ($('#upload-failed').length){
				$('#upload-failed').remove();
			}

			// delete preloader spinner
			$('#preloader').remove();

			// remove current profile pic
			$('#profile-pic').empty();

			// create thumbnail src
			var rndstr = Math.random().toString(36).substring(7);
			var thumb = '<div class="dummy container-img" style="background-image:url(\'<?= $pic_baseurl; ?>/<?= $profile_full_folder; ?>/<?= $folder ?>/' + response + '?' + rndstr + '\');"></div>';

			// display uploaded pic's thumb
			// empty original thumb

			$('#profile-pic').append(thumb);

		} // end onComplete
	}); // end new AjaxUpload

	// user delete profile pic
	$('#delete-profile-pic').click(function(){
		var post_url = 'delete-profile-pic.php';
		$.post(post_url, function(data){

			// create thumbnail src
			var blank = '<img src="../imgs/blank.png" width="150" height="150" />';

			// display uploaded pic's thumb
			// empty original thumb
			$('#profile-pic').empty();
			$('#profile-pic').append(blank);
		});
	});
});  // end $(document).ready(function() {
</script>