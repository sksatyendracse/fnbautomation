<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang; ?>"> <![endif]-->
<html lang="<?= $html_lang; ?>">
<head>
<title><?= $txt_html_title; ?> - <?= $site_name; ?></title>
<meta name="robots" content="noindex">
<?php require_once(__DIR__ . '/_user_html_head.php'); ?>
</head>
<body class="tpl-edit-place">
<?php require_once(__DIR__ . '/_user_header.php'); ?>

<div class="wrapper">
	<div class="full-block">
		<h1><?= $txt_main_title; ?></h1>

		<p class="sub-header"><?= $txt_sub_header; ?></p>

		<div class="form-wrapper">
			<form method="post" id="the_form" action="<?= $baseurl; ?>/user/process-edit-place.php">
				<input type="hidden" name="csrf_token" value="<?= session_id(); ?>">
				<input type="hidden" id="submit_token" name="submit_token" value="<?= $submit_token; ?>" />
				<input type="hidden" id="latlng" name="latlng" value="(<?= $lat; ?>, <?= $lng; ?>)" />
				<input type="hidden" id="place_id" name="place_id" value="<?= $place_id; ?>"/>

				<p><?= $txt_click_map; ?> (*)</p>

				<div id="map-wrapper">
					<div id="map-canvas" style="width:100%; height:100%"></div>
				</div>

				<div class="form-row">
					<div class="form-row-full">
						<div><label for="place_name"><?= $txt_label_place_name; ?></label></div>
						<div><input type="text" id="place_name" name="place_name" required value="<?= $place_name ;?>"/></div>

					</div>
				</div>

				<h3><?= $txt_title_address; ?></h3>

				<div class="form-row">
					<div class="form-row-half">
						<div><label for="address"><?= $txt_label_address; ?></label></div>
						<div><input type="text" id="address" name="address" value="<?= $address ;?>" required></div>
					</div>

					<div class="form-row-half">
						<div><label for="postal_code"><?= $txt_label_postal_code; ?></label></div>
						<div><input type="text" id="postal_code" name="postal_code" value="<?= $postal_code ;?>" /></div>
					</div>

					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="form-row-half">
						<div><label for="cross_street"><?= $txt_label_cross_street; ?></label></div>
						<div><input type="text" id="cross_street" name="cross_street" value="<?= $cross_street ;?>" /></div>
					</div>

					<div class="form-row-half">
						<div><label for="neighborhood"><?= $txt_label_neighborhood; ?></label></div>
						<div><input type="text" id="neighborhood_name" name="neighborhood_name" value="<?= $neighborhood_name ;?>" /></div>
					</div>
					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="form-row-half">
						<div><label for="city_id"><?= $txt_label_city; ?></label></div>
						<div>
							<select id="city_id" name="city_id" />
								<option value="<?= $city_id; ?>" selected="selected"><?= $city_name; ?>, <?= $state_abbr; ?></option>
								<?php
								if(!$cfg_use_select2) {
									$stmt = $conn->prepare("SELECT * FROM cities LIMIT $cfg_city_dropdown_limit");
									$stmt->execute();

									while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?= $row['city_id'] ?>"><?= $row['city_name'] ?>, <?= $row['state'] ?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-row-half">
						<div><label for="inside"><?= $txt_label_inside; ?> <a class="the-tooltip" data-toggle="tooltip" data-placement="top" title="<?= $txt_tooltip_inside; ?>"><i class="fa fa-question-circle"></i></a></label></div>
						<div><input type="text" id="inside" name="inside" value="<?= $inside; ?>" /></div>
					</div>

					<div class="clear"></div>
				</div>

				<h3><?= $txt_title_contact; ?></h3>

				<div class="form-row">
					<div class="form-row-half">
						<div><label for="area"><?= $txt_label_phone; ?></label></div>
						<div>
							<input type="text" id="area" name="area_code" value="<?= $area_code ;?>" />
							<input type="tel" id="phone" name="phone" value="<?= $phone ;?>" />
						</div>
					</div>

					<div class="form-row-half">
						<div><label for="twitter"><?= $txt_label_twitter; ?></label></div>
						<div><input type="text" id="twitter" name="twitter" value="<?= $twitter ;?>" /></div>
					</div>

					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="form-row-half">
						<div><label for="website"><?= $txt_label_website; ?></label></div>
						<div><input type="url" id="website" name="website" pattern="https?://.+" value="<?= $website ;?>" /></div>
					</div>

					<div class="form-row-half">
						<div><label for="facebook"><?= $txt_label_facebook; ?></label></div>
						<div><input type="text" id="facebook" name="facebook" value="<?= $facebook ;?>" /></div>
					</div>

					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="form-row-half"> &nbsp;
					</div>

					<div class="form-row-half">
						<div><label for="foursq_id"><?= $txt_label_foursquare; ?> <a class="the-tooltip" data-toggle="tooltip" data-placement="top" title="<?= $txt_tooltip_foursquare; ?>"><i class="fa fa-question-circle"></i></a></label></div>
						<div><input type="text" id="foursq_id" name="foursq_id" value="<?= $foursq_id ;?>" /></div>
					</div>

					<div class="clear"></div>
				</div>

				<div class="form-row">
					<div class="form-row-full">
						<div><label for="description"><?= $txt_label_description; ?></label></div>
						<div><textarea id="description" name="description" /><?= $description ;?></textarea></div>
					</div>
				</div>

				<!-- categories -->
				<h3><?= $txt_title_categories; ?></h3>

				<div class="form-row">
					<div class="form-row-full">
						<div><label for="category_id"><?= $txt_label_category; ?></label></div>
						<div>
							<select id="category_id" name="category_id">
							<option value="">select category</option>
							<?php show_cat_dropdown(0, 0, $cat_id, $conn); ?>
							</select>
						</div>
					</div>
				</div>
				<!-- end categories -->

				<!-- custom fields -->
				<?php require_once($plugin_dir . '/custom_fields/user-form-block.php'); ?>
				<!-- end custom fields plugin -->

				<h3><?= $txt_title_hours; ?></h3>

				<div id="selected-hours">
					<?php
					foreach($business_hours as $k => $v) {
						?>
						<div class="hours-row">
							<span class="weekday"><strong><?php echo $v['day']; ?></strong></span>
							<span class="start"><?php echo $v['display_open']; ?></span> <span>-</span><span class="end"><?php echo $v['display_close']; ?></span>
							<a class="remove-hours"><i class="fa fa-times"></i></a>
							<input type="hidden" name="business_hours[]" value="<?php echo $v['day_int']; ?>,<?php echo $v['open']; ?>,<?php echo $v['close']; ?>">
						</div>
						<?php
					}
					?>
				</div>

				<div class="form-row">
					<select class="hours-control" id="hours-weekday">
						<option value="0"><?= $txt_week_mon; ?></option>
						<option value="1"><?= $txt_week_tue; ?></option>
						<option value="2"><?= $txt_week_wed; ?></option>
						<option value="3"><?= $txt_week_thu; ?></option>
						<option value="4"><?= $txt_week_fri; ?></option>
						<option value="5"><?= $txt_week_sat; ?></option>
						<option value="6"><?= $txt_week_sun; ?></option>
					</select>

					<select class="hours-control" id="hours-start">
						<option value="0000">12:00 am (<?= $txt_midnight; ?>)</option>
						<option value="0030">12:30 am </option>
						<option value="0100">1:00 am </option>
						<option value="0130">1:30 am </option>
						<option value="0200">2:00 am </option>
						<option value="0230">2:30 am </option>
						<option value="0300">3:00 am </option>
						<option value="0330">3:30 am </option>
						<option value="0400">4:00 am </option>
						<option value="0430">4:30 am </option>
						<option value="0500">5:00 am </option>
						<option value="0530">5:30 am </option>
						<option value="0600">6:00 am </option>
						<option value="0630">6:30 am </option>
						<option value="0700">7:00 am </option>
						<option value="0730">7:30 am </option>
						<option value="0800">8:00 am </option>
						<option value="0830">8:30 am </option>
						<option value="0900" selected="">9:00 am </option>
						<option value="0930">9:30 am </option>
						<option value="1000">10:00 am </option>
						<option value="1030">10:30 am </option>
						<option value="1100">11:00 am </option>
						<option value="1130">11:30 am </option>
						<option value="1200">12:00 pm (<?= $txt_noon; ?>)</option>
						<option value="1230">12:30 pm </option>
						<option value="1300">1:00 pm </option>
						<option value="1330">1:30 pm </option>
						<option value="1400">2:00 pm </option>
						<option value="1430">2:30 pm </option>
						<option value="1500">3:00 pm </option>
						<option value="1530">3:30 pm </option>
						<option value="1600">4:00 pm </option>
						<option value="1630">4:30 pm </option>
						<option value="1700">5:00 pm </option>
						<option value="1730">5:30 pm </option>
						<option value="1800">6:00 pm </option>
						<option value="1830">6:30 pm </option>
						<option value="1900">7:00 pm </option>
						<option value="1930">7:30 pm </option>
						<option value="2000">8:00 pm </option>
						<option value="2030">8:30 pm </option>
						<option value="2100">9:00 pm </option>
						<option value="2130">9:30 pm </option>
						<option value="2200">10:00 pm </option>
						<option value="2230">10:30 pm </option>
						<option value="2300">11:00 pm </option>
						<option value="2330">11:30 pm </option>
					</select>

					<select class="hours-control" id="hours-end">
						<option value="0000">12:00 am (<?= $txt_midnight; ?>)</option>
						<option value="0030">12:30 am </option>
						<option value="0100">1:00 am </option>
						<option value="0130">1:30 am </option>
						<option value="0200">2:00 am </option>
						<option value="0230">2:30 am </option>
						<option value="0300">3:00 am </option>
						<option value="0330">3:30 am </option>
						<option value="0400">4:00 am </option>
						<option value="0430">4:30 am </option>
						<option value="0500">5:00 am </option>
						<option value="0530">5:30 am </option>
						<option value="0600">6:00 am </option>
						<option value="0630">6:30 am </option>
						<option value="0700">7:00 am </option>
						<option value="0730">7:30 am </option>
						<option value="0800">8:00 am </option>
						<option value="0830">8:30 am </option>
						<option value="0900">9:00 am </option>
						<option value="0930">9:30 am </option>
						<option value="1000">10:00 am </option>
						<option value="1030">10:30 am </option>
						<option value="1100">11:00 am </option>
						<option value="1130">11:30 am </option>
						<option value="1200">12:00 pm (<?= $txt_noon; ?>)</option>
						<option value="1230">12:30 pm </option>
						<option value="1300">1:00 pm </option>
						<option value="1330">1:30 pm </option>
						<option value="1400">2:00 pm </option>
						<option value="1430">2:30 pm </option>
						<option value="1500">3:00 pm </option>
						<option value="1530">3:30 pm </option>
						<option value="1600">4:00 pm </option>
						<option value="1630">4:30 pm </option>
						<option value="1700" selected="">5:00 pm </option>
						<option value="1730">5:30 pm </option>
						<option value="1800">6:00 pm </option>
						<option value="1830">6:30 pm </option>
						<option value="1900">7:00 pm </option>
						<option value="1930">7:30 pm </option>
						<option value="2000">8:00 pm </option>
						<option value="2030">8:30 pm </option>
						<option value="2100">9:00 pm </option>
						<option value="2130">9:30 pm </option>
						<option value="2200">10:00 pm </option>
						<option value="2230">10:30 pm </option>
						<option value="2300">11:00 pm </option>
						<option value="2330">11:30 pm </option>
					</select>

					<button class="btn-hours-control" type="button" id="btn-add-hours"><?= $txt_btn_add_hours; ?></button>
				</div>

				<div class="form-row">
					<div class="form-row-half">
						<div><label for="hours_note"><?= $txt_label_hours_notes; ?> <a class="the-tooltip" data-toggle="tooltip" data-placement="top" title="<?= $txt_tooltip_hours_note; ?>"><i class="fa fa-question-circle"></i></a></label></div>
						<div><input type="text" id="business_hours_info" name="business_hours_info" value="<?= $business_hours_info ;?>" /></div>
					</div>

					<div class="form-row-half"> &nbsp;
					</div>

					<div class="clear"></div>
				</div>

				<!-- photos -->
				<h3><?= $txt_title_photos; ?></h3>

				<div class="form-row">
					<div><label><?= $txt_label_form_upload; ?></label></div>
					<div><a id="upload-button" class="btn btn-default"><?= $txt_form_upload_button; ?></a></div>
				</div>

				<div class="form-row">
					<div id="uploaded">
						<!-- uploaded pics -->
						<?php
						foreach($place_photos as $v) {
							?>
							<div class="thumbs">
								<img src="<?= $pic_baseurl; ?>/<?= $place_thumb_folder ?>/<?= $v['dir']; ?>/<?= $v['filename']; ?>" width="132">
								<div class="btn-delete-thumb delete_existing_pic"><i class="fa fa-times-circle-o"></i></div>
								<input type="hidden" name="existing_pics[]" value="<?= str_replace("&#65279;","",$v['filename']); ?>">
								<input type="hidden" name="deidtddsfafd"/>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<!-- end photos -->

				<div class="form-row submit-row">
					<div><input type="submit" id="submit_button" name="submit_button" value="<?= $txt_form_submit_button; ?>" class="btn btn-blue" /></div>
				</div>
			</form>
		</div><!-- .form-wrapper ->
	</div><!-- .full-block -->
</div><!-- .wrapper -->

<?php require_once(__DIR__ . '/_user_footer.php'); ?>

<?php
$js_inc = __DIR__ . '/../js/user_js/js-edit-place.php';

if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>