<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<?php
if($plan_type != 'free' && $plan_type != 'free_feat') {
	?>
	<title><?= $txt_html_title ?> - <?= $site_name ?></title>
	<?php
}
else {
	?>
	<title><?= $txt_html_title_free ?> - <?= $site_name ?></title>
	<?php
}
?>
<meta name="robots" content="noindex">
<?php require_once(__DIR__ . '/_user_html_head.php') ?>
</head>
<body class="tpl-process-add-place">
<?php require_once(__DIR__ . '/_user_header.php') ?>

<div class="wrapper">
	<div class="full-block align-center">
		<?php
		if($plan_type != 'free' && $plan_type != 'free_feat' && !$is_admin) {
			?>
			<h1><?= $txt_main_title ?></h1>

			<div class="checkout-box-wrapper clearfix">
				<div class="checkout-box">
					<p><?= $result_message ?></p>

					<?php
					if(!$has_errors) {
						?>
						<h2><?= $txt_order_details_title ?></h2>

						<p><?= $txt_selected_plan ?>: <strong><?= $plan_name ?></strong><br>
						<?= $txt_plan_value ?>: <strong><?= $currency_symbol ?> <?= $plan_price ?></strong></p>

						<?php
						if($paypal_mode != -1) {
							?>
							<div class="block">
								<form action="<?= $paypal_url ?>" method="post" id="<?= $plan_type ?>-<?= $plan_id ?>">
									<input type="hidden" name="cmd"           value="<?= $cmd ?>">
									<input type="hidden" name="notify_url"    value="<?= $notify_url ?>">
									<input type="hidden" name="bn"            value="<?= $bn ?>">
									<input type="hidden" name="business"      value="<?= $paypal_merchant_id ?>">
									<input type="hidden" name="item_name"     value="<?= $plan_name ?> - <?= $site_name ?>">
									<input type="hidden" name="currency_code" value="<?= $currency_code ?>">
									<input type="hidden" name="custom"        value="<?= $place_id ?>">
									<input type="hidden" name="image_url"     value="<?= $paypal_checkout_logo_url ?>">
									<input type="hidden" name="lc"            value="<?= $paypal_locale ?>">
									<input type="hidden" name="return"        value="<?= $baseurl ?>/user/thanks">
									<input type="hidden" name="charset"       value="utf-8">

									<!-- amounts -->
									<?php
									if($plan_type == 'monthly' || $plan_type == 'monthly_feat') {
										?>
										<input type="hidden" name="a3"  value="<?= $a3 ?>">
										<input type="hidden" name="p3"  value="<?= $p3 ?>">
										<input type="hidden" name="t3"  value="<?= $t3 ?>">
										<input type="hidden" name="src" value="1">
										<?php
									}
									if($plan_type == 'one_time' || $plan_type == 'one_time_feat') {
										?>
										<input type="hidden" name="amount" value="<?= $amount ?>">
										<?php
									}
									if($plan_type == 'annual' || $plan_type == 'annual_feat') {
										?>
										<input type="hidden" name="a3"  value="<?= $a3 ?>">
										<input type="hidden" name="p3"  value="<?= $p3 ?>">
										<input type="hidden" name="t3"  value="<?= $t3 ?>">
										<input type="hidden" name="src" value="1">
										<?php
									}
									?>

									<!-- the submit button -->
									<div class="form-row">
										<button type="submit" id="submit" name="submit">
										<img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png" alt="Buy now with PayPal" />
										</button>
									</div>
								</form>
							</div>
						<?php
						} // end paypal

						// stripe
						if($stripe_mode != -1) {
							?>
							<div class="block">
								<form action="<?= $baseurl ?>/_msg.php" method="POST">
									<input type="hidden" name="plan_type" value="<?= $plan_type ?>">
									<input type="hidden" name="plan_id" value="<?= $plan_id ?>">
									<input type="hidden" name="place_id" value="<?= $place_id ?>">
									<input type="hidden" name="ref" value="stripe">
									<script
										src="https://checkout.stripe.com/checkout.js" class="stripe-button"
										data-key         = "<?= $stripe_key ?>"
										data-amount      = "<?= $stripe_amount ?>"
										data-currency    = "<?= $stripe_data_currency ?>"
										data-name        = "<?= $plan_name ?>"
										data-description = "<?= $stripe_data_description ?>"
										data-image       = "<?= $stripe_data_image ?>"
										data-locale      = "auto">
									</script>
								</form>
							</div>
							<?php
						} // end stripe

						// mercadopago
						if($mercadopago_mode != -1) {
							if(!empty($button_link)) {
								?>
								<div class="form-row">
									<a href="<?= $button_link['response']['init_point'] ?>" class="btn btn-blue" style="padding: 4px 24px; font-weight: 700"><?= $txt_btn_mercadopago ?></a><br>
									<img src="https://secure.mlstatic.com/components/resources/mp/images/mercadopago-logo-166x44.png">
								</div>
							<?php
							}
							else {
								?>
								<p><?= $txt_invalid_email ?></p>
								<?php
							}
						} // end mercadopago

					}
				?>
				</div>
			</div>
		<?php
		}
		else {
		?>
			<h1><?= $txt_main_title_free ?></h1>

			<?= $txt_thanks ?>
		<?php
		}
		?>
	</div>
</div>

<?php require_once(__DIR__ . '/_user_footer.php') ?>

<?php
$js_inc = __DIR__ . '/../js/user_js/js-process-add-place.php';

if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>