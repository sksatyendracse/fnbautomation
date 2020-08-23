<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang; ?>"> <![endif]-->
<html lang="<?= $html_lang; ?>">
<head>
<title><?= $txt_html_title; ?></title>
<meta name="description" content="<?= $txt_meta_desc; ?>" />
<link rel="canonical" href="<?= $canonical; ?>" />
<?php require_once('_html_head.php'); ?>

<!-- Owl carousel -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css" />
</head>
<body class="tpl-index">

<?php require_once('_header.php'); ?>

<div class="wrapper">
	<?php
	if(!empty($main_cats)) {
		?>
		<div class="full-block">
			<div class="home-main-cats">
				<?php
				$i = 1;
				foreach($main_cats as $v) {
					?>
					<div class="main-cat-block block-<?= $i; ?>">
						<div class="main-cat-icon">
							<a href="<?= $baseurl; ?>/<?= $loc_slug; ?>/list/<?= $v['cat_slug']; ?>/<?= $loc_type; ?>-<?= $loc_id; ?>-<?= $v['cat_id']; ?>-1">
							<?= $v['iconfont_tag']; ?>
							</a>
						</div>
						<div class="main-cat-name">
							<a href="<?= $baseurl; ?>/<?= $loc_slug; ?>/list/<?= $v['cat_slug']; ?>/<?= $loc_type; ?>-<?= $loc_id; ?>-<?= $v['cat_id']; ?>-1">
							<?= $v['cat_name']; ?>
							</a>
						</div>
					</div>
					<?php
					$i++;
				}
				?>
				<div class="clear"></div>
			</div><!-- home-main-categories -->
		</div><!-- .full-block -->
		<?php
	}
	?>

	<?php
	if(!empty($featured_listings)) {
		?>
		<h2><?= $txt_featuredvenues; ?></h2>

		<div class="position-relative" style="position:relative">
			<!-- Carousel controls -->
			<a class="slide-btn slidePrev text-primary shadow-1">
				<i class="fa fa-chevron-left text-muted"></i>
			</a>
			<a class="slide-btn slideNext text-primary shadow-1">
				<i class="fa fa-chevron-right text-muted"></i>
			</a>

			<style>
	.owl-carousel .featured-item {
		width: 100%;
	}

	.owl-carousel .featured-item .featured-item-rating img {
		display: inline-block;
		width: 16px;
	}

.slide-btn {
	width: 48px;
	height: 48px;
	line-height: 48px;
	border: 0;
	position: absolute;
	margin-top: -24px;
	text-align: center;
	top: 45%;
	z-index: 120;
	cursor: pointer;
	background-color: #fff;
	border-radius: 30px;
}

a.slide-btn:not([href]):not([tabindex]) {
	color: #fff;
	cursor: pointer;
}

.slideNext {
	right: -24px;
	position: absolute;
}

.slidePrev {
	left: -24px;
	position: absolute;
}
			</style>

			<div class="owl-carousel">
				<?php
				foreach($featured_listings as $k => $v) {
					?>
					<div class="featured-item">
						<a href="<?= $v['place_link']; ?>" title="<?= $v['place_name']; ?>">
							<div class="featured-item-pic" id="<?= $v['place_id']; ?>">
								<img src="<?= $v['photo_url']; ?>">
							</div><!-- .featured-item-pic -->

							<div class="featured-item-rating" data-rating="<?= $v['rating']; ?>">
								<!-- raty plugin placeholder -->
							</div>

							<h3><?= $v['place_name']; ?></h3>
						</a>

						<?php
						if(!empty($v['profile_pic'])) {
							?>
							<div class="user-card">
								<div class="user-pic">
									<img src="<?= $v['profile_pic']; ?>" style="width:88px;display:inline-block;">
								</div>

								<div class="user-name">
									<h3><?= $v['user_name']; ?></h3>
								</div>
							</div><!-- .user-card -->

							<div class="featured-item-description">
								<?= $v['tip_text'];	?>
								<a href="<?= $v['place_link']; ?>"><strong style="font-size: 1.2rem;"><?= $txt_read_more; ?></strong></a>
							</div><!-- .featured-item-desc -->
							<?php
						}

						else {
							?>
							<div class="featured-item-description" style="padding-top:24px">
								<?= $v['place_desc']; ?> <a href="<?= $v['place_link']; ?>"><strong style="font-size: 1.2rem;"><?= $txt_read_more; ?></strong></a>
							</div><!-- .featured-item-desc -->
							<?php
						}
						?>
					</div><!-- .featured-item  -->
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
	?>

	<?php
	if(!empty($latest_listings)) {
		?>
		<h2><?= $txt_lastest_listings; ?></h2>

		<div class="home-trending-venues">
			<?php
			foreach($latest_listings as $k => $v) {
				?>
				<div class="featured-item">
					<a href="<?= $v['place_link']; ?>" title="<?= $v['place_name']; ?>">
						<div class="featured-item-pic" id="<?= $v['place_id']; ?>">
							<img src="<?= $v['photo_url']; ?>">
						</div>

						<div class="featured-item-rating" data-rating="<?= $v['rating']; ?>">
							<!-- raty plugin placeholder -->
						</div>

						<h3><?= $v['place_name']; ?></h3>
					</a>

					<?php
					if(!empty($v['profile_pic'])) {
						?>
						<div class="user-card">
							<div class="user-pic">
								<img src="<?= $v['profile_pic']; ?>" />
							</div>

							<div class="user-name">
								<h3><?= $v['user_name']; ?></h3>
							</div>
						</div>

						<div class="featured-item-description">
							<?= $v['tip_text'];	?>
							<a href="<?= $v['place_link']; ?>"><strong style="font-size: 1.2rem;"><?= $txt_read_more; ?></strong></a>
						</div>
						<?php
					}

					else {
						?>
						<div class="featured-item-description" style="padding-top:24px">
							<?= $v['place_desc']; ?> <a href="<?= $v['place_link']; ?>"><strong style="font-size: 1.2rem;"><?= $txt_read_more; ?></strong></a>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
			<div class="clear"></div>
		</div>
		<?php
	}
	?>

	<?php
	if(!empty($featured_cities)) {
		?>
		<h2><?= $txt_popularcities; ?></h2>

		<div class="home-popular-cities full-block">
			<ul>
				<?php
				foreach($featured_cities as $k => $v) {
					?>
					<li><a href="<?= $baseurl; ?>/<?= $v['city_slug']; ?>/list/all-categories/c-<?= $v['city_id']; ?>-0-1"><?= $v['city_name']; ?></a></li>
					<?php
				}
				?>
			</ul>
			<div class="clear"></div>
		</div>
	<?php
	}
	?>
</div>

<?php require_once('_footer.php'); ?>

<!-- Owl carousel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<?php
$js_inc = __DIR__ . '/js/js-index.php';
if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>