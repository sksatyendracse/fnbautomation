<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?> - <?= $site_name ?></title>
<meta name="robots" content="noindex">
<?php require_once(__DIR__ . '/_user_html_head.php') ?>
</head>
<body class="tpl-sign-up">
<?php require_once(__DIR__ . '/_user_header.php') ?>

<div class="wrapper wrapper-720">
	<div class="full-block">
		<h1><?= $txt_main_title ?></h1>

		<p class="sub-heading"></p>

		<?php
		if(empty($form_submitted)) {
			?>
			<div class="form-block">
				<div id="sign-up-result"></div>

				<div class="login-form">
					<form id="sign-up-form">
						<?php
						$referrer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
						?>
						<input type="hidden" id="referrer" name="referrer" value="<?php echo $referrer ?>" />

						<div class="form-row">
							<input type="text" id="fname" class="" name="fname" tabindex="0" placeholder="<?= $txt_label_fname ?>" required>
						</div>

						<div class="form-row">
							<input type="text" id="lname" class="" name="lname" tabindex="0" placeholder="<?= $txt_label_lname ?>">
						</div>

						<div class="form-row">
							<input type="email" id="email" class="field text fn" name="email" tabindex="0" placeholder="<?= $txt_label_email ?>" required>
						</div>

						<div class="form-row">
							<input type="password" id="password" class="field text fn" name="password" tabindex="0" placeholder="<?= $txt_label_passw ?>" autocomplete="false" required>
						</div>

						<div class="form-row" style="text-align: center">
							<button id="submit-btn" class="btn btn-blue"><?= $txt_submit_btn ?></button>
						</div>
					</form>
				</div>

				<div class="social-login">
					<?php
					if(!empty($facebook_key) && !empty($facebook_secret)) {
						?>
						<div class="social-login-button">
							<a class="facebook-icon" href="<?= $baseurl ?>/user/login.php?provider=facebook"><i class="fa fa-facebook"></i> <?= $txt_btn_facebook  ?></a>
						</div>
						<?php
					}
					?>

					<?php
					if(!empty($twitter_key) && !empty($twitter_secret)) {
						?>
						<div class="social-login-button">
							<a class="twitter-icon" href="<?= $baseurl ?>/user/login.php?provider=twitter"><i class="fa fa-twitter"></i> <?= $txt_btn_twitter  ?></a>
						</div>
						<?php
					}
					?>
				</div>

				<div class="clear"></div>
			</div><!-- .form-block -->

			<?= $txt_has_account ?>  <a href="login.php"><?= $txt_link_log_in ?></a></p>
			<?php
		}
		?>
	</div>
</div>

<?php require_once(__DIR__ . '/_user_footer.php') ?>

<?php
$js_inc = __DIR__ . '/../js/user_js/js-sign-up.php';

if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>