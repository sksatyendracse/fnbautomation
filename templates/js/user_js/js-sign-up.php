<!-- sign up form -->
<script>
$(document).ready(function(){
	$('#sign-up-results').hide();

	$('#submit-btn').click(function(e) {
		// to use checkValidity, we need to get the form element without jquery
		var the_form = document.getElementById('sign-up-form');

		// check validity and process form
		if(the_form.checkValidity()) {
			e.preventDefault();

			// show spinner
			$('#submit-btn').empty();
			$('#submit-btn').html('<i class="fa fa-spinner fa-spin"></i> <?= $txt_loading ?>');

			// disable submit button
			$('#submit-btn').attr("disabled", "disabled");

			// vars
			var url = '<?= $baseurl ?>/user/process-sign-up.php';

			// hide form and show spinner
			$('#sign-up-result').show();

			// ajax post
			$.post(url, {
				params: $('#sign-up-form').serialize(),
			}, function(data) {
				// debug
				console.log(data);

				// hide form
				$('.login-form').remove();
				$('.social-login').remove();

				// responses
				if(data == 'user_exists') {
					$('#sign-up-result').html('<div class="alert alert-danger" role="alert"><?= $txt_email_exists ?></div>').fadeIn();
				}

				if(data == 'user_created') {
					$('#sign-up-result').html('<div class="alert alert-success" role="alert"><?= $txt_acct_created ?> <?= $txt_acct_created_explain ?></div>').fadeIn();
				}

				if(data == 'mailer_problem') {
					$('#sign-up-result').html('<div class="alert alert-danger" role="alert">Mailer problem, contact the administrator</div>').fadeIn();
				}

				if(data == 'invalid_email') {
					$('#sign-up-result').html('<div class="alert alert-danger" role="alert"><?= $txt_invalid_email ?></div>').fadeIn();
				}
			});
		}
	});


});
</script>