<?php
$home_active          = 0;
$listings_active      = 0;
$cats_active          = 0;
$reviews_active       = 0;
$users_active         = 0;
$plans_active         = 0;
$locations_active     = 0;
$settings_active      = 0;
$pages_active         = 0;
$emails_active        = 0;
$txn_history_active   = 0;
$tools_active         = 0;
$custom_fields_active = 0;
$coupons_active       = 0;
$blogs_active         = 0;
$setting_active         = 0;
$notification_active         = 0;

if(basename($_SERVER['SCRIPT_NAME']) == 'setting_.php') {
	$setting_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'notifications.php') {
	$notification_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'index.php') {
	$home_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-listings.php') {
	$listings_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-cats.php') {
	$cats_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'list-category-add.php') {
	$cats_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'list-category-edit.php') {
	$cats_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-reviews.php') {
	$reviews_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-users.php') {
	$users_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-plans.php') {
	$plans_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-locations.php') {
	$locations_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-settings.php') {
	$settings_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-pages.php') {
	$pages_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-create-page.php') {
	$pages_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-emails.php') {
	$emails_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-txn-history.php') {
	$txn_history_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-tools.php') {
	$tools_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'admin-coupons.php') {
	$coupons_active = 1;
}

if(basename($_SERVER['SCRIPT_NAME']) == 'blog-category') {
	$blogs_active = 1;
}

if(!isset($txt_menu_coupons)) {
	$txt_menu_coupons = "Coupons";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Best Local Directory Website</title>
	<!-- META TAGS -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- FAV ICON(BROWSER TAB ICON) -->
	<link rel="shortcut icon" href="<?= $baseurl; ?>/images/fav.ico" type="image/x-icon">
	<!-- GOOGLE FONT -->
	<link href="https://fonts.googleapis.com/css?family=Poppins%7CQuicksand:500,700" rel="stylesheet">
	<!-- FONTAWESOME ICONS -->
	<link rel="stylesheet" href="<?= $baseurl; ?>/admin/css/font-awesome.min.css">
	<!-- ALL CSS FILES -->
	<link href="<?= $baseurl; ?>/admin/css/materialize.css" rel="stylesheet">
	<link href="<?= $baseurl; ?>/admin/css/style.css" rel="stylesheet">
	<link href="<?= $baseurl; ?>/admin/css/bootstrap.css" rel="stylesheet" type="text/css">
	<!-- RESPONSIVE.CSS ONLY FOR MOBILE AND TABLET VIEWS -->
	<link href="<?= $baseurl; ?>/admin/css/responsive.css" rel="stylesheet">
	<link href="<?= $baseurl; ?>/admin/css/pnotify.all.min.css" rel="stylesheet">
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
	<!--== MAIN CONTRAINER ==-->
	<div class="container-fluid sb1">
		<div class="row">
			<!--== LOGO ==-->
			<div class="col-md-2 col-sm-3 col-xs-6 sb1-1">
				<a href="#" class="btn-close-menu"><i class="fa fa-times" aria-hidden="true"></i></a>
				<a href="#" class="atab-menu"><i class="fa fa-bars tab-menu" aria-hidden="true"></i></a>
				<a href="<?= $baseurl; ?>/admin" class="logo"><img src="<?= $baseurl; ?>/admin/images/logo/logo1.png" alt=""> </a>
			</div>
			<!--== SEARCH ==-->
			<div class="col-md-6 col-sm-6 mob-hide">
				<!-- <form class="app-search">
					<input type="text" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a>
				</form> -->
			</div>
			<!--== NOTIFICATION ==-->
			<div class="col-md-2 tab-hide">
				<!-- <div class="top-not-cen">
					<a class='waves-effect btn-noti' href='#'>
						<i class="fa fa-commenting-o" aria-hidden="true"></i>
						<span>5</span>
					</a>
					<a class='waves-effect btn-noti' href='#'>
						<i class="fa fa-envelope-o" aria-hidden="true"></i>
						<span>5</span>
					</a>
					<a class='waves-effect btn-noti' href='#'>
						<i class="fa fa-tag" aria-hidden="true"></i>
						<span>5</span>
					</a>
				</div> -->
			</div>
			<!--== MY ACCCOUNT ==-->
			<div class="col-md-2 col-sm-3 col-xs-6">
				<!-- Dropdown Trigger -->
				<a class='waves-effect dropdown-button top-user-pro' href='#' data-activates='top-menu'><img src="<?= $baseurl; ?>/admin/images/users/6.png" alt="">My Account <i class="fa fa-angle-down" aria-hidden="true"></i> </a>
				<!-- Dropdown Structure -->
				<ul id='top-menu' class='dropdown-content top-menu-sty'>
					<li><a href="<?= $baseurl; ?>/admin/setting/" class="waves-effect"><i class="fa fa-cogs"></i>Admin Setting</a> </li>
					<li><a href="<?= $baseurl; ?>/admin/notifications/"><i class="fa fa-bell-o"></i>Notifications</a> </li>
					<li class="divider"></li>
					<li><a href="<?= $baseurl; ?>/user/logoff" class="ho-dr-con-last waves-effect"><i class="fa fa-sign-in" aria-hidden="true"></i> Logout</a> </li>
				</ul>
			</div>
		</div>
	</div>
	<!--== BODY CONTNAINER ==-->
	<div class="container-fluid sb2">
		<div class="row">
			<div class="sb2-1">
				<!--== USER INFO ==-->
				<div class="sb2-12">
					<ul>
						<li><img src="<?= $baseurl; ?>/admin/images/users/2.png" alt=""> </li>
						<li>
							<h5>John Smith <span> Santa Ana, CA</span></h5> </li>
						<li></li>
					</ul>
				</div>
				<!--== LEFT MENU ==-->
				<div class="sb2-13">
					<ul class="collapsible" data-collapsible="accordion">
						<li><a  href="<?= $baseurl; ?>/admin/" <?= ($home_active) ? 'class="menu-active"' : ''; ?>><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a> </li>
						<li><a href="javascript:void(0)" class="collapsible-header"><i class="fa fa-list-ul" aria-hidden="true"></i> Listing</a>
							<div class="collapsible-body left-sub-menu">
								<ul>
									<li><a href="<?= $baseurl; ?>/admin/admin-listings/">All listing</a> </li>
									<li><a href="<?= $baseurl ?>/user/add-place/2">Add New listing</a> </li>
									<li><a href="<?= $baseurl; ?>/admin/admin-cats/">listing Categories</a> </li>
									<li><a href="<?= $baseurl; ?>/admin/list-category-add/">Add listing Categories</a> </li>
								</ul>
							</div>
						</li>
						<!-- <li><a href="projects.php"><i class="fa fa-book" aria-hidden="true"></i> Projects</a> </li> -->
						<!-- <li><a href="javascript:void(0)" class="collapsible-header"><i class="fa fa-user" aria-hidden="true"></i> Users</a>
							<div class="collapsible-body left-sub-menu">
								<ul>
									<li><a href="admin-all-users.html">All Users</a> </li>
									<li><a href="admin-list-users-add.html">Add New user</a> </li>
								</ul>
							</div>
						</li>
						<li><a href="admin-analytics.html"><i class="fa fa-bar-chart" aria-hidden="true"></i> Analytics</a> </li>
						<li><a href="javascript:void(0)" class="collapsible-header"><i class="fa fa-buysellads" aria-hidden="true"></i>Ads</a>
							<div class="collapsible-body left-sub-menu">
								<ul>
									<li><a href="admin-ads.html">All Ads</a> </li>
									<li><a href="admin-ads-create.html">Create New Ads</a> </li>
								</ul>
							</div>
						</li>
						<li><a href="admin-payment.html"><i class="fa fa-usd" aria-hidden="true"></i> Payments</a> </li>
						<li><a href="admin-earnings.html"><i class="fa fa-money" aria-hidden="true"></i> Earnings</a> </li> -->
						<li><a href="<?= $baseurl; ?>/admin/notifications/" class=""><i class="fa fa-bell-o"></i>Notifications</a></li>
						<li><a href="javascript:void(0)" class="collapsible-header"><i class="fa fa-tags" aria-hidden="true"></i> Pages</a>
							<div class="collapsible-body left-sub-menu">
								<ul>
									<li><a href="<?= $baseurl; ?>/admin/pages/">Pages</a> </li>
								</ul>
							</div>
						</li>
						<li><a href="javascript:void(0)" class="collapsible-header"><i class="fa fa-rss" aria-hidden="true"></i> Blog & Articals</a>
							<div class="collapsible-body left-sub-menu">
								<ul>
									<li><a href="<?= $baseurl; ?>/admin/blog/">All Blogs</a> </li>
									<li><a href="<?= $baseurl; ?>/admin/blog-add/">Add Blog</a> </li>
									<li><a href="<?= $baseurl; ?>/admin/blog-category/">Blog Categories</a> </li>
									<li><a href="<?= $baseurl; ?>/admin/blog-category-add/">Add Blog Categories</a></li>
								</ul>
							</div>
						</li>
						<li><a href="<?= $baseurl; ?>/admin/setting/"><i class="fa fa-cogs" aria-hidden="true"></i> Setting</a> </li>
						<!-- <li><a href="social-media.php"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Social Media</a> </li> -->

					</ul>
				</div>
			</div>
