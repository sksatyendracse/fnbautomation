<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang; ?>" > <![endif]-->
<html lang="<?= $html_lang; ?>" >
<head>
<title><?= $txt_html_title; ?> - <?= $site_name; ?></title>
<meta name="robots" content="noindex">
<?php require_once(__DIR__ . '/_user_html_head.php'); ?>

</head>
<body class="tpl-my-profile">
<?php require_once(__DIR__ . '/_user_header.php'); ?>

<div class="wrapper">
	<div class="menu-box">
		<?php require_once(__DIR__ . '/_user_menu.php'); ?>
	</div>

	<div class="main-container">
		<h2><?= $txt_main_title; ?> <span style="font-size: 12px; font-weight: normal">(<a href="<?= $baseurl; ?>/profile/<?= $userid; ?>"><?= $txt_public_profile_link; ?></a>)</span></h2>

		<div class="padding">
			<form method="post" action="process-edit-profile.php">
				<input type="hidden" name="csrf_token" value="<?= session_id(); ?>">
				<div class="form-row">
					<div class="label-col">
						<label for="first_name"><?= $txt_label_picture; ?></label>
					</div>
					<div class="field-col">
						<div id="profile-pic">
							<?= $profile_pic_tag; ?>
						</div>

						<div class="profile-pic-controls">
							<div class="block">
								<a href="#" id="upload-profile-pic" class="btn btn-ghost"><i class="fa fa-upload"></i> <?= $txt_btn_change; ?></a>
							</div>
							<div class="block">
								<a href="#" id="delete-profile-pic" class="btn btn-ghost"><i class="fa fa-trash"></i> <?= $txt_btn_delete; ?></a>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="label-col">
						<label for="first_name"><?= $txt_label_fname; ?></label>
					</div>
					<div class="field-col">
						<input type="text" id="first_name" name="first_name" class="form-control" value="<?= $first_name; ?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="label-col">
						<label for="last_name"><?= $txt_label_lname; ?></label>
					</div>
					<div class="field-col">
						<input type="text" id="last_name" name="last_name" class="form-control" value="<?= $last_name; ?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="label-col">
						<label><?= $txt_label_gender; ?></label>
					</div>
					<div class="field-col">
						<label><input type="radio" id="gender" name="gender" value="f" <?php if($gender == 'f') echo 'checked="checked"'; ?> /> <?= $txt_value_female; ?></label>
						<label><input type="radio" id="gender" name="gender" value="m" <?php if($gender == 'm') echo 'checked="checked"'; ?> /> <?= $txt_value_male; ?></label>
						<label><input type="radio" id="gender" name="gender" value="u" <?php if($gender == 'u') echo 'checked="checked"'; ?> /> <?= $txt_value_other_gender; ?></label>
					</div>
					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="label-col">
						<label><?= $txt_label_birthday; ?></label>
					</div>
					<div class="field-col">
						<div class="select">
							<select id="b_month" name="b_month" class="b_month">
								<?php
								for($i = 0; $i < 13; $i++) {
									$selected = ($b_month == $i) ? 'selected="selected"' : '';
									?>
									<option value="<?= $i; ?>" <?= $selected; ?>><?= $months[$i]; ?></option>
									<?php
								}
								?>
							</select>
						</div>
						<div class="select">
							<select id="b_day" name="b_day" class="b_day">
								<option value="0"><?= $txt_value_day; ?></option>
								<?php
								for($i = 1; $i < 32; $i++) {
									$selected = ($b_day == $i) ? 'selected="selected"' : '';
									?>
									<option value="<?= $i; ?>" <?= $selected; ?>><?= $i; ?></option>
									<?php
								}
								?>
							</select>
						</div>

						<div class="select">
							<select id="b_year" name="b_year" class="b_year">
								<?php
								for($i = date("Y") - 100; $i < date("Y") - 18; $i++) {
									$selected = ($b_year == $i) ? 'selected="selected"' : '';
									?>
									<option value="<?= $i; ?>" <?= $selected; ?>><?= $i; ?></option>
									<?php
								}
								?>
							</select>
						</div><!-- .select -->
					</div><!-- .field-col -->
					<div class="clear"></div>
				</div><!-- .form-row -->

				<div class="form-row">
					<div class="label-col">
						<label for="email"><?= $txt_label_email; ?></label>
					</div>
					<div class="field-col">
						<input type="text" id="email" name="email" class="form-control" value="<?= $email; ?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="label-col">
						<label for="profile_city"><?= $txt_label_city; ?></label>
					</div>
					<div class="field-col">
						<input type="text" id="profile_city" name="profile_city" class="form-control" value="<?= $profile_city; ?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="label-col">
						<label for="profile_city"><?= $txt_label_country; ?></label>
					</div>
					<div class="field-col">
						<input type="text" id="profile_country" name="profile_country" class="form-control" value="<?= $profile_country; ?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="form-row submit-row">
					<div><input type="submit" id="submit" name="submit" value="<?= $txt_btn_submit; ?>" class="btn btn-blue"></div>
				</div>
			</form>
		</div><!-- .padding -->
	</div><!-- .main-container -->

	<div class="clear"></div>
</div><!-- .wrapper -->
<?php require_once(__DIR__ . '/_user_footer.php'); ?>

<?php
$js_inc = __DIR__ . '/../js/user_js/js-my-profile.php';

if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>