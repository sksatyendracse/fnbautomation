<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?> - <?= $site_name ?></title>
<meta name="robots" content="noindex">
<?php require_once('_html_head.php') ?>
<style>
.form-control {
	width:60%;
}
</style>
</head>
<body class="tpl-contact">
<?php require_once('_header.php') ?>
<div class="wrapper">
	<div class="full-block">
		<h1><?= $txt_main_title ?></h1>

		<div id="contact-result"></div>

		<form id="contact-form">
			<div class="form-group">
				<label for="name"><?= $txt_name ?></label>
				<input type="text" id="name" class="form-control" name="name">
			</div>

			<div class="form-group">
				<label for="email"><?= $txt_email ?></label>
				<input type="email" id="email" class="form-control" name="email">
			</div>

			<div class="form-group">
				<label for="email"><?= $txt_subject ?></label>
				<input type="text" id="subject" class="form-control" name="subject">
			</div>

			<div class="form-group">
				<label for="message"><?= $txt_message ?></label>
				<textarea id="message" class="form-control" name="message" rows="3"></textarea>
			</div>

			<button type="submit" id="submit-contact" class="btn btn-blue">Submit</button>
		</form>
	</div>
</div>

<?php require_once('_footer.php') ?>

<?php
$js_inc = __DIR__ . '/js/js-contact.php';
if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>