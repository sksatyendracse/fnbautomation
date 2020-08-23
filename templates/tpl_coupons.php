 <!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang; ?>"> <![endif]-->
<html lang="<?= $html_lang; ?>">
<head>
<title><?= $txt_html_title; ?> - <?= $site_name; ?></title>
<meta name="description" content="<?= $txt_meta_desc; ?>">
<link rel="canonical" href="<?= $canonical; ?>">
<?php require_once('_html_head.php'); ?>

<!-- css -->
<link rel="stylesheet" href="<?= $baseurl ?>/templates/css/bootstrap-social.css">
</head>
<body class="tpl-coupons">
<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php require_once('_header.php'); ?>
<div class="wrapper">
	<h1><?= $txt_html_title; ?></h1>

	<div class="full-block">
		<div class="content-col">
			<?php
			if(!$is_single) {
				?>
				<div class="list-items">
					<?php
					if(!empty($coupons_arr)) {
						foreach($coupons_arr as $k => $v) {
							?>
							<div class="item" id="coupon-<?= $v['coupon_id'] ?>">
								<div class="item-pic coupon-img" id="<?= $v['coupon_id'] ?>">
									<a href=""><img src="<?= $v['coupon_img'] ?>" /></a>
								</div><!-- .user-item-pic -->

								<div class="item-description coupon-body">
									<div class="item-title-row block">
										<h2><a href=""><?= e($v['coupon_title']) ?></a></h2>
									</div>

									<div class="block"><?= e($v['coupon_description']) ?></div>

									<div class="block">
										<a href="<?= $baseurl ?>/coupons/<?= $v['coupon_id'] ?>" class="btn btn-green"><strong><?= $txt_view_details ?></strong></a>
									</div>

									<div class="block">
										<!-- tweet button -->
										<a href="<?= $v['twitter_link'] ?>"
										class="btn btn-default btn-even-less-padding">
										<i class="fa fa-twitter" aria-hidden="true"></i> <?= $txt_tweet ?></a>

										<!-- facebook share -->
										<a href="#"
										class="btn btn-default btn-even-less-padding"
										onclick="window.open('<?= $v['facebook_link'] ?>','facebook-share-dialog', 'width=626,height=436'); return false;">
										<i class="fa fa-facebook" aria-hidden="true"></i> <?= $txt_share ?></a>

										<!-- mail -->
										<a href="<?= $v['mailto_link'] ?>"
										class="btn btn-default btn-even-less-padding">
										<i class="fa fa-envelope" aria-hidden="true"></i> <?= $txt_mail ?></a>

										<!-- print -->
										<a href="<?= $v['coupon_img'] ?>" target="_blank"
										class="btn btn-default btn-even-less-padding">
										<i class="fa fa-print" aria-hidden="true"></i> <?= $txt_print ?></a>
									</div>

								</div><!-- .item-description -->
								<div class="clear"></div>
							</div><!-- .item -->
							<?php
						}
					} //  end foreach($response as $k => $v)
					?>
				</div><!-- .list-items -->

				<!-- begin pagination -->
				<div id="pager">
					<ul class="pagination">
						<?= $pager_template; ?>
					</ul>
				</div><!-- #pager -->
			<?php
			}

			else {
				?>
				<div class="list-items">

					<div style="float:left;width:240px;margin-right:24px">
						<img src="<?= $coupon_img_url ?>" width="240">
					</div>

					<div style="float:left;width:calc(100% - 264px);">
						<h2 style="margin-bottom:0"><?= $coupon_title ?></h2>
						<p><?= $txt_created_by ?>: <a href="<?= $place_link; ?>"><?= $place_name ?></a></p>

						<div class="block"><?= nl2p($coupon_description) ?></div>

						<!-- print -->
						<?php
						if($coupon_valid == 'valid') {
							?>
							<a href="<?= $coupon_img_url ?>" target="_blank"
							class="btn btn-default">
							<i class="fa fa-print" aria-hidden="true"></i> <?= $txt_print ?></a>

							<span class="btn btn-default">
							<i class="fa fa-clock-o" aria-hidden="true"></i> <?= $txt_expires ?>: <?= $coupon_expire ?></span>
							<?php
						}

						else {
							?>
							<a href="<?= $coupon_img_url ?>" target="_blank"
							class="btn btn-default">
							<i class="fa fa-print" aria-hidden="true"></i> <?= $txt_expired ?></a>
							<?php
						}
						?>

						<a href="<?= $place_link; ?>" class="btn btn-blue"><?= $txt_about_coupon ?></a>
					</div>
				</div>
				<?php
			}
			?>
		</div><!-- .content-col -->

		<div class="sidebar">
			<?php
			if(!empty($list_items)) {
				?>
				<div class="map-wrapper" id="sticker">
					<div id="map-canvas" style="width:100%; height:100%"></div>
				</div>
				<?php
			}
			?>
		</div><!-- .sidebar -->

		<div class="clear"></div>
	</div><!-- .full-block -->
</div><!-- .wrapper -->

<?php require_once('_footer.php'); ?>

<?php
$js_inc = __DIR__ . '/js/js-coupons.php';
if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>