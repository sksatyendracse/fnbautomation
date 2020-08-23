<!DOCTYPE html>
<html lang="en">
<head>
	<title>World Best Local Directory Website template</title>
	<!-- META TAGS -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- FAV ICON(BROWSER TAB ICON) -->
	<link rel="shortcut icon" href="images/fav.ico" type="image/x-icon">
	<!-- GOOGLE FONT -->
	<link href="https://fonts.googleapis.com/css?family=Poppins%7CQuicksand:500,700" rel="stylesheet">
	<!-- FONTAWESOME ICONS -->
	<link rel="stylesheet" href="<?= $baseurl; ?>/templates/css/font-awesome.min.css">
	<!-- ALL CSS FILES -->
	<link href="<?= $baseurl; ?>/templates/css/materialize.css" rel="stylesheet">
	<link href="<?= $baseurl; ?>/templates/css/style.css" rel="stylesheet">
	<link href="<?= $baseurl; ?>/templates/css/bootstrap.css" rel="stylesheet" type="text/css">
	<!-- RESPONSIVE.CSS ONLY FOR MOBILE AND TABLET VIEWS -->
	<link href="<?= $baseurl; ?>/templates/css/responsive.css" rel="stylesheet">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div id="preloader">
		<div id="status">&nbsp;</div>
	</div>
	<section class="tz-login">
		<div class="tz-regi-form">
			<h4>Admin</h4>
			<p>Sign In</p>
			<form class="col s12" method="post" action="<?= $baseurl; ?>/user/login.php">
				<div class="row">
					<div class="input-field col s12">
						<input type="text" class="validate" name="email">
						<label>User Name</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input type="password" name="password" class="validate">
						<label>Password</label>
					</div>
					<input type="hidden" name="referrer" value="<?= $baseurl; ?>/admin">
				</div>
				<div class="row">
					<div class="input-field col s12"> <em class="waves-effect waves-light btn-large full-btn waves-input-wrapper" style="">
					<input type="submit" id="submit" value="submit" class="waves-button-input"></em> </div>
				</div>
			</form>
			<p><a  href="password-request.php">Forgot password</a> </p>
		</div>
	</section>
	<!--SCRIPT FILES-->
	<script src="<?= $baseurl; ?>/templates/js/jquery.min.js"></script>
	<script src="<?= $baseurl; ?>/templates/js/bootstrap.js" type="text/javascript"></script>
	<script src="<?= $baseurl; ?>/templates/js/materialize.min.js" type="text/javascript"></script>
	<script src="<?= $baseurl; ?>/templates/js/custom.js"></script>
	<?php
	$js_inc = __DIR__ . '/../js/user_js/js-login.php';

	if(file_exists($js_inc)) {
		include_once($js_inc);
	}
	?>
</body>
</html>