<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang; ?>"> <![endif]-->
<html lang="<?= $html_lang; ?>">
<head>
<title><?= $page_title; ?> - <?= $site_name; ?></title>
<meta name="description" content="<?= $meta_desc; ?>" />
<?php require_once('_html_head.php'); ?>
</head>
<body class="tpl-page">
<?php require_once('_header.php'); ?>

<div class="wrapper">
	<div class="full-block">
		<?= $page_contents; ?>
	</div><!-- .full-block -->
</div><!-- .wrapper -->

<?php require_once('_footer.php'); ?>

<?php
$js_inc = __DIR__ . '/js/js-page.php';
if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>