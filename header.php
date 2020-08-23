<?php
// main categories
$query = "SELECT * FROM blog_category";
$stmt = $conn->prepare($query);
$stmt->execute();

$blog_categories = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$cur_loop = array(
		'id'       => $row['id'],
		'name'     => $row['name']
	);
	$blog_categories[] = $cur_loop;
}

$query = "SELECT * FROM settings WHERE id=1";
$stmt = $conn->prepare($query);
$stmt->execute();
$settings = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$settings[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>World Best Local Directory Website</title>
	<!-- META TAGS -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- FAV ICON(BROWSER TAB ICON) -->
	<link rel="shortcut icon" href="<?php echo $baseurl;?>/images/fav.ico" type="image/x-icon">
	<!-- GOOGLE FONT -->
	<link href="https://fonts.googleapis.com/css?family=Poppins%7CQuicksand:500,700" rel="stylesheet">
	<!-- FONTAWESOME ICONS -->
	<link rel="stylesheet" href="<?php echo $baseurl;?>/css/font-awesome.min.css">
	<!-- ALL CSS FILES -->
	<link href="<?php echo $baseurl;?>/css/materialize.css" rel="stylesheet">
	<link href="<?php echo $baseurl;?>/css/style.css" rel="stylesheet">
	<link href="<?php echo $baseurl;?>/css/bootstrap.css" rel="stylesheet" type="text/css">
	<!-- RESPONSIVE.CSS ONLY FOR MOBILE AND TABLET VIEWS -->
	<link href="<?php echo $baseurl;?>/css/responsive.css" rel="stylesheet">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<!--PRE LOADING-->
	<div id="preloader">
		<div id="status">&nbsp;</div>
	</div>
	<!--BANNER AND SERACH BOX-->
	<section>
		<div class="v3-top-menu">
			<div class="container">
				<div class="row">
					<div class="v3-menu">
						<div class="v3-m-1">
							<a href="<?php echo $baseurl;?>"><img src="<?php echo $baseurl;?>/images/logo/logo1.png" alt=""> </a>
						</div>

						<div class="v3-m-2">
							<ul>
								<li><a class='' href='<?php echo $baseurl;?>/blog.php?id=<?php echo $blog_categories[0]['id'];?>'><?php echo $blog_categories[0]['name'];?></a></li>
								<li><a class='' href='<?php echo $baseurl;?>/blog.php?id=<?php echo $blog_categories[1]['id'];?>'><?php echo $blog_categories[1]['name'];?></a></li>
								<li><a class='' href='<?php echo $baseurl;?>/blog.php?id=<?php echo $blog_categories[2]['id'];?>'><?php echo $blog_categories[2]['name'];?></a></li>
								<li><a class='' href='<?php echo $baseurl;?>/blog.php?id=<?php echo $blog_categories[3]['id'];?>'><?php echo $blog_categories[3]['name'];?> </a></li>
								<li><a class='dropdown-button ed-sub-menu' href='#' data-activates='blog-menu'>More</a></li>

								<!-- <li><a href="db-listing-add.html" class="v3-add-bus"><i class="fa fa-plus" aria-hidden="true"></i> Create Listing</a> </li> -->
							</ul>
						</div>
					</div>


					<div class="all-drop-down-menu">
						<!--DROP DOWN MENU: HOME-->
						<ul id='blog-menu' class='dropdown-content'>
							<?php
							 foreach ($blog_categories as $key => $v) {
								 if($key < 4) {
									 continue;
								 }
							?>
							<li><a class='' href='<?php echo $baseurl;?>/blog.php?id=<?php echo $v['id'];?>'><?php echo $v['name'];?></a></li>
							<?php
							  }
							?>
						</ul>
						<!--END DROP DOWN MENU-->
					</div>
				</div>
			</div>
		</div>
	</section>

	<section>
		<div class="v3-mob-top-menu">
			<div class="container">
				<div class="row">
					<div class="v3-mob-menu">
						<div class="v3-mob-m-1">
							<a href="index-1.html"><img src="images/logo2.png" alt=""> </a>
						</div>
						<div class="v3-mob-m-2">
							<div class="v3-top-ri">
								<ul>
									<li><a href="login.html" class="v3-menu-sign"><i class="fa fa-sign-in"></i> Sign In</a> </li>
									<li><a href="price.html" class="v3-add-bus"><i class="fa fa-plus" aria-hidden="true"></i> Add Listing</a> </li>
									<li><a href="#" class="ts-menu-5" id="v3-mob-menu-btn"><i class="fa fa-bars" aria-hidden="true"></i>Menu</a> </li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="mob-right-nav" data-wow-duration="0.5s">
			<div class="mob-right-nav-close"><i class="fa fa-times" aria-hidden="true"></i> </div>
			<h5>Business</h5>
			<ul class="mob-menu-icon">
				<li><a href="price.html">Add Business</a> </li>
				<li><a href="#!" data-toggle="modal" data-target="#register">Register</a> </li>
				<li><a href="#!" data-toggle="modal" data-target="#sign-in">Sign In</a> </li>
			</ul>
			<h5>All Categories</h5>
			<ul>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Help Services</a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Appliances Repair & Services</a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Furniture Dealers</a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Packers and Movers</a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Pest Control </a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Solar Product Dealers</a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Interior Designers</a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Carpenters</a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Plumbing Contractors</a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Modular Kitchen</a> </li>
				<li><a href="list.html"><i class="fa fa-angle-right" aria-hidden="true"></i> Internet Service Providers</a> </li>
			</ul>
		</div>
	</section>
