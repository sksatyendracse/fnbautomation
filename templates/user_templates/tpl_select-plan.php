<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?> - <?= $site_name ?></title>
<?php require_once(__DIR__ . '/_user_html_head.php') ?>

<style>


@media (min-width: 768px) {
	.row.equal {
		display: flex;
		flex-wrap: wrap;
	}
}

.panel {
    width: 100%;
    height: 100%;
}

.panel-footer {
    position: absolute;
    right: 0;
    bottom: 0;
    left: 0;
}
</style>
</head>
<body class="tpl-select-plan">
<?php require_once(__DIR__ . '/_user_header.php') ?>

<div class="wrapper">
	<div class="full-block">
		<h1><?= $txt_main_title ?></h1>

		<div class="container-fluid">
			<div class="row equal">
				<?php
				if(!empty($plans_arr)) {
					foreach($plans_arr as $k => $v) {
						if(
							!(($v['plan_type'] == 'free' || $v['plan_type'] == 'free_feat') && $free_count > ($cfg_max_free_listings - 1))
						) {
						?>
						<div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 24px">
							<div class="panel panel-info plan-box" style="position:relative">
								<div class="panel-heading">
									<h2 class="text-center"><?= $v['plan_name'] ?></h2>
								</div>

								<div class="panel-body text-center" style="padding-bottom:70px">
									<p class="lead" style="font-size:40px"><strong><?= $currency_symbol ?> <?= $v['plan_price'] ?></strong></p>
									<?= $v['plan_description1'] ?><br>
									<?= $v['plan_description2'] ?><br>
									<?= $v['plan_description3'] ?><br>
									<?= $v['plan_description4'] ?><br>
									<?= $v['plan_description5'] ?><br>
								</div>

								<div class="panel-footer" style="">
									<a href="<?= $baseurl ?>/user/add-place/<?= $v['plan_id'] ?>" class="btn btn-lg btn-block btn-blue"><?= $txt_buy_now ?></a>
								</div>
							</div>
						</div>
						<?php
						}
					}
				}

				else {
					?>
					<div class="alert alert-info" role="alert">
						<?= $txt_no_plans ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>

<?php require_once(__DIR__ . '/_user_footer.php') ?>

<?php
$js_inc = __DIR__ . '/../js/user_js/js-select-plan.php';

if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>