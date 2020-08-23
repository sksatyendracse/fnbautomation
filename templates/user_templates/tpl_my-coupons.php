<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>" > <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?> - <?= $site_name ?></title>
<meta name="robots" content="noindex">
<?php require_once(__DIR__ . '/_user_html_head.php'); ?>
</head>
<body class="tpl-my-places">
<?php require_once(__DIR__ . '/_user_header.php'); ?>

<div class="wrapper">
	<div class="menu-box">
		<?php require_once('_user_menu.php'); ?>
	</div>
	<div class="main-container">
		<h2><?= $txt_my_coupons ?></h2>

		<?php
		if($user_has_listings == true && $coupon_create == true) {
			?>
			<div class="padding">
				<form method="post" action="process-create-coupon.php">
					<input type="hidden" name="csrf_token" value="<?= session_id() ?>">

					<div class="form-row">
						<strong><?= $txt_coupon_title ?></strong><br>
						<input type="text" id="coupon_title" name="coupon_title" class="form-control">
					</div>

					<div class="form-row">
						<strong><?= $txt_coupon_description ?></strong><br>
						<textarea id="coupon_description" name="coupon_description" class="form-control"></textarea>
					</div>

					<div class="form-row block" id="coupon-img-row">
						<input type="file" id="coupon_img" name="coupon_img" style="display:block;visibility:hidden;width:0;height:0;">
						<input type="hidden" name="uploaded_img" id="uploaded_img" value="">

						<strong><?= $txt_coupon_img ?></strong><br>

						<div class="block">
							<div id="coupon-img" style="width:<?= $coupon_size[0] ?>;height:<?= $coupon_size[1] ?>"></div>
						</div>

						<div class="coupon-img-controls">
							<div class="block">
								<span id="upload-coupon-img" class="btn btn-default"><i class="fa fa-plus"></i> upload</span>
							</div>
						</div>
					</div>

					<div class="form-row">
						<strong><?= $txt_coupon_expire ?></strong><br>
						<input type="date" id="coupon_expire" name="coupon_expire" class="form-control" required>
					</div>

					<div class="form-row">
						<strong><?= $txt_coupon_place ?></strong><br>
						<select id="coupon_place_id" name="coupon_place_id" class="form-control">
							<?php
							foreach($user_places as $v) {
								?>
								<option value="<?= $v['place_id'] ?>"><?= e($v['place_name']) ?></option>
								<?php
							}
							?>
						</select>
					</div>

					<div class="form-row submit-row align-center">
						<input type="submit" id="submit" name="submit" class="btn btn-blue">
					</div>
				</form>
			</div>
			<?php
		}

		if($user_has_listings == true && $coupon_create == false) {
			?>
			<div class="padding">
				<a href="?event=create" class="btn btn-blue btn-less-padding"><i class="fa fa-plus" aria-hidden="true"></i>
		 <?= $txt_coupon_create ?></a>
			</div>

			<div class="padding">
				<?php
				if(!empty($coupons_arr)) {
					foreach($coupons_arr as $k => $v) {
						?>
						<div class="block">
							<div class="user-item coupon" id="coupon-<?= $v['coupon_id'] ?>">
								<div class="user-item-pic coupon-img" id="<?= $v['coupon_id'] ?>">
									<a href=""><img src="<?= $v['coupon_img'] ?>" /></a>
								</div><!-- .user-item-pic -->

								<div class="user-item-description coupon-body">
									<div class="user-item-title block" id="coupon-<?= $v['coupon_id'] ?>">
										<h3><a href=""><?= e($v['coupon_title']) ?></a></h3>
									</div>

									<div class="block"><?= e($v['coupon_description']) ?></div>

									<!-- controls -->
									<div class="controls">
										<a href="?event=delete&coupon=<?= $v['coupon_id'] ?>" class="btn btn-default btn-less-padding"><i class="fa fa-trash" aria-hidden="true"></i> <?= $txt_coupon_del ?></a>
									</div>
								</div><!-- .user-item-description -->
								<div class="clear"></div>
							</div><!-- .user-item -->
						</div><!-- .block -->
						<?php
					} // end foreach
				} // end if($total_rows > 0)
				else {
					?>
					<div class="block"><?= $txt_no_coupons ?></div>
					<?php
				}
				?>
			</div><!-- .padding -->
		<?php
		} // end if ($user_has_listings == true && $coupon_create == false)
		?>
	</div><!-- .main-container -->

	<div class="clear"></div>
</div><!-- .wrapper -->

<?php require_once(__DIR__ . '/_user_footer.php'); ?>

<?php
$js_inc = __DIR__ . '/../js/user_js/js-my-coupons.php';

if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>
