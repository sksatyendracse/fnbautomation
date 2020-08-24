<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');
require_once(__DIR__ . '/../admin/_admin_inc.php'); // checks session and user id
require_once($lang_folder . '/admin_translations/trans-index.php');
require_once(__DIR__ . '/../admin/_admin_header.php');
// path info
$frags = '';
$frags = '';

if(!empty($_SERVER['PATH_INFO'])) {
	$frags = $_SERVER['PATH_INFO'];
}

else {
	if(!empty($_SERVER['ORIG_PATH_INFO'])) {
		$frags = $_SERVER['ORIG_PATH_INFO'];
	}
}

// frags still empty
if(empty($frags)) {
	$frags = (!empty($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
}

$frags = explode("/", $frags);

foreach($frags as $k => $v) {
	$frags[$k] = e($v);
}

$place_id = !empty($frags[1]) ? $frags[1] : 0;

if(empty($place_id)) {
	throw new Exception('Place id cannot be empty');
}

$query = "SELECT * FROM places
	WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$place_userid        = $row['userid'];
$lat                 = $row['lat'];
$lng                 = $row['lng'];
$place_name          = $row['place_name'];
$address             = $row['address'];
$postal_code         = $row['postal_code'];
$cross_street        = $row['cross_street'];
$neighborhood        = $row['neighborhood'];
$city_id             = $row['city_id'];
$state_id            = $row['state_id'];
$inside              = $row['inside'];
$area_code           = $row['area_code'];
$phone               = $row['phone'];
$email               = $row['email'];
$twitter             = $row['twitter'];
$facebook            = $row['facebook'];
$foursq_id           = $row['foursq_id'];
$website             = $row['website'];
$description         = $row['description'];
$business_hours_info = $row['business_hours_info'];
$submission_date     = $row['submission_date'];
$status              = $row['status'];
$opening_hour        = $row['opening_hour'];
$closing_hour        = $row['closing_hour'];

// check if user owns this place
if($place_userid != $userid && 0) {
	// logged in userid is different from this place's userid
	// maybe it's an admin
	if(!$is_admin) {
		die('no permission to edit ' . $place_name);
	}
}

// filter vars, sanitize
$place_name          = filter_var($place_name         , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$address             = filter_var($address            , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$postal_code         = filter_var($postal_code        , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$cross_street        = filter_var($cross_street       , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$neighborhood        = filter_var($neighborhood       , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$inside              = filter_var($inside             , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$phone               = filter_var($phone              , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$twitter             = filter_var($twitter            , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$facebook            = filter_var($facebook           , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$foursq_id           = filter_var($foursq_id          , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$website             = filter_var($website            , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$description         = filter_var($description        , FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$business_hours_info = filter_var($business_hours_info, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

// get city details
$query = "SELECT * FROM cities WHERE city_id = :city_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':city_id', $city_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$city_name = $row['city_name'];
$state_abbr = $row['state'];

// get neighborhood details
$query = "SELECT * FROM neighborhoods WHERE neighborhood_id = :neighborhood_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':neighborhood_id', $neighborhood);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$neighborhood_slug = $row['neighborhood_slug'];
$neighborhood_name = $row['neighborhood_name'];

// get cat details
$query = "SELECT * FROM rel_place_cat INNER JOIN cats ON rel_place_cat.cat_id = cats.id WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$place_cats = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$place_cats[] = $row['cat_id'];
}

/*--------------------------------------------------
business hours
--------------------------------------------------*/
$query = "SELECT * FROM business_hours WHERE place_id = :place_id ORDER BY day";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$business_hours = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$day     = $row['day'];
	$cur_loop_arr = array(
		'day'           => $day
	);
	$business_hours[] = $day;
}


/*--------------------------------------------------
photos
--------------------------------------------------*/
$query = "SELECT * FROM photos WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$place_photos = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$place_photos[] = array(
		'photo_id' => $row['photo_id'],
		'dir' => $row['dir'],
		'filename' => $row['filename']
	);
}

/*--------------------------------------------------
translation replacements
--------------------------------------------------*/
$txt_sub_header = str_replace('%place_name%', $place_name, $txt_sub_header);

/*--------------------------------------------------
session to prevent multiple form submissions
--------------------------------------------------*/
$submit_token = uniqid('', true);
$_SESSION['submit_token'] = $submit_token;
?>
<script>
localStorage.setItem("submit_token", "<?php echo $submit_token;?>")
</script>
<div class="sb2-2">
				<!--== breadcrumbs ==-->
				<div class="sb2-2-2">
					<ul>
						<li><a href="<?= $baseurl; ?>/admin/"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
						<li class="active-bre"><a href="#"> Edit Listing</a> </li>
						<li class="page-back"><a href="<?= $baseurl; ?>/admin/"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>Edit Listing</h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-inn-page">
									<div class="tab-inn ad-tab-inn">
										<div class="hom-cre-acc-left hom-cre-acc-right">
											<div class="">
											<form method="post" id="the_form" action="<?= $baseurl; ?>/user/process-edit-place.php" enctype="multipart/form-data">
											<input type="hidden" id="submit_token" name="submit_token" value="<?= $_SESSION['submit_token']; ?>">
											<input type="hidden" name="csrf_token" value="<?= session_id(); ?>">
											<input type="hidden" id="latlng" name="latlng">
											<input type="hidden" id="plan_id" name="plan_id" value="2">
											<input type="hidden" id="place_id" name="place_id" value="<?php echo $place_id;?>">

													
													<div class="row">
														<div class="input-field col s12">
															<input id="list_name" type="text" class="validate" name="place_name" value="<?php echo $place_name;?>">
															<label for="list_name">Listing Title</label>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12">
															<input id="list_phone" type="text" class="validate"  name="phone" value="<?php echo $phone;?>">
															<label for="list_phone">Phone</label>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12">
															<input id="email" type="email" class="validate" name="email" value="<?php echo $email;?>">
															<label for="email">Email</label>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12">
															<input id="list_addr" type="text" class="validate" name="address" value="<?php echo $address;?>">
															<label for="list_addr">Address</label>
														</div>
													</div>
													<!-- <div class="row">
														<div class="input-field col s12">
															<select>
																<option value="" disabled="" selected="">Listing Type</option>
																<option value="1">Free</option>
																<option value="2">Premium</option>
																<option value="3">Premium Plus</option>
																<option value="3">Ultra Premium Plus</option>
															</select>
														</div>
													</div> -->
													<div class="row">
														<div class="input-field col s12">
															<select name="city_id">
																<option value="" disabled="" selected="">Choose your city</option>
																<?php
																if($cfg_use_select2) {
																	$stmt = $conn->prepare("SELECT * FROM cities LIMIT $cfg_city_dropdown_limit");
																	$stmt->execute();

																	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
																		?>
																		<option <?php echo ($row['city_id'] == $city_id)?'selected':'' ;?> value="<?= $row['city_id'] ?>"><?= $row['city_name'] ?> <?= $row['state'] ?></option>
																		<?php
																	}
																}
																?>
															</select>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12">
															<select multiple="" name="category_id[]">
																<option value="" disabled="" selected="">Select Category</option>
																<?php get_children(0, 0, 0, $conn, $place_cats); ?>
															</select>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12">
															<select multiple="" name="opening_day[]">
																<option value="" disabled="" selected="">Opening Days</option>
																<option value="7" <?php echo in_array(7, $business_hours)?'selected':'' ;?>>All Days</option>
																<option value="0" <?php echo in_array(0, $business_hours)?'selected':'' ;?>>Monday</option>
																<option value="1" <?php echo in_array(1, $business_hours)?'selected':'' ;?>>Tuesday</option>
																<option value="2" <?php echo in_array(2, $business_hours)?'selected':'' ;?>>Wednesday</option>
																<option value="3" <?php echo in_array(3, $business_hours)?'selected':'' ;?>>Thursday</option>
																<option value="4" <?php echo in_array(4, $business_hours)?'selected':'' ;?>>Friday</option>
																<option value="5" <?php echo in_array(5, $business_hours)?'selected':'' ;?>>Saturday</option>
																<option value="6" <?php echo in_array(6, $business_hours)?'selected':'' ;?>>Sunday</option>
															</select>
														</div>
													</div>
												<div class="row">
													<div class="input-field col s6">
														<select name="opening_hour">
															<option value="" disabled="" selected="">Open Time</option>
															<option value="12:00 AM" <?php echo ("12:00 AM" == $opening_hour)?'selected':'' ;?>>12:00 AM</option>
															<option value="01:00 AM" <?php echo ("01:00 AM" == $opening_hour)?'selected':'' ;?>>01:00 AM</option>
															<option value="02:00 AM" <?php echo ("02:00 AM" == $opening_hour)?'selected':'' ;?>>02:00 AM</option>
															<option value="03:00 AM" <?php echo ("03:00 AM" == $opening_hour)?'selected':'' ;?>>03:00 AM</option>
															<option value="04:00 AM" <?php echo ("04:00 AM" == $opening_hour)?'selected':'' ;?>>04:00 AM</option>
															<option value="05:00 AM" <?php echo ("05:00 AM" == $opening_hour)?'selected':'' ;?>>05:00 AM</option>
															<option value="06:00 AM" <?php echo ("06:00 AM" == $opening_hour)?'selected':'' ;?>>06:00 AM</option>
															<option value="07:00 AM" <?php echo ("07:00 AM" == $opening_hour)?'selected':'' ;?>>07:00 AM</option>
															<option value="08:00 AM" <?php echo ("08:00 AM" == $opening_hour)?'selected':'' ;?>>08:00 AM</option>
															<option value="09:00 AM" <?php echo ("09:00 AM" == $opening_hour)?'selected':'' ;?>>09:00 AM</option>
															<option value="10:00 AM" <?php echo ("10:00 AM" == $opening_hour)?'selected':'' ;?>>10:00 AM</option>
															<option value="11:00 AM" <?php echo ("11:00 AM" == $opening_hour)?'selected':'' ;?>>11:00 AM</option>
															<option value="12:00 AM" <?php echo ("12:00 AM" == $opening_hour)?'selected':'' ;?>>12:00 PM</option>
															<option value="01:00 PM" <?php echo ("01:00 PM" == $opening_hour)?'selected':'' ;?>>01:00 PM</option>
															<option value="02:00 PM" <?php echo ("02:00 PM" == $opening_hour)?'selected':'' ;?>>02:00 PM</option>
															<option value="03:00 PM" <?php echo ("03:00 PM" == $opening_hour)?'selected':'' ;?>>03:00 PM</option>
															<option value="04:00 PM" <?php echo ("04:00 PM" == $opening_hour)?'selected':'' ;?>>04:00 PM</option>
															<option value="05:00 PM" <?php echo ("05:00 PM" == $opening_hour)?'selected':'' ;?>>05:00 PM</option>
															<option value="06:00 PM" <?php echo ("06:00 PM" == $opening_hour)?'selected':'' ;?>>06:00 PM</option>
															<option value="07:00 PM" <?php echo ("07:00 PM" == $opening_hour)?'selected':'' ;?>>07:00 PM</option>
															<option value="08:00 PM" <?php echo ("08:00 PM" == $opening_hour)?'selected':'' ;?>>08:00 PM</option>
															<option value="09:00 PM" <?php echo ("09:00 PM" == $opening_hour)?'selected':'' ;?>>09:00 PM</option>
															<option value="10:00 PM" <?php echo ("10:00 PM" == $opening_hour)?'selected':'' ;?>>10:00 PM</option>
															<option value="11:00 PM" <?php echo ("11:00 PM" == $opening_hour)?'selected':'' ;?>>11:00 PM</option>
														</select>
													</div>
													<div class="input-field col s6">
														<select name="closing_hour">
															<option value="" disabled="" selected="">Closing Time</option>
															<option value="12:00 AM" <?php echo ("12:00 AM" == $closing_hour)?'selected':'' ;?>>12:00 AM</option>
															<option value="01:00 AM" <?php echo ("01:00 AM" == $closing_hour)?'selected':'' ;?>>01:00 AM</option>
															<option value="02:00 AM" <?php echo ("02:00 AM" == $closing_hour)?'selected':'' ;?>>02:00 AM</option>
															<option value="03:00 AM" <?php echo ("03:00 AM" == $closing_hour)?'selected':'' ;?>>03:00 AM</option>
															<option value="04:00 AM" <?php echo ("04:00 AM" == $closing_hour)?'selected':'' ;?>>04:00 AM</option>
															<option value="05:00 AM" <?php echo ("05:00 AM" == $closing_hour)?'selected':'' ;?>>05:00 AM</option>
															<option value="06:00 AM" <?php echo ("06:00 AM" == $closing_hour)?'selected':'' ;?>>06:00 AM</option>
															<option value="07:00 AM" <?php echo ("07:00 AM" == $closing_hour)?'selected':'' ;?>>07:00 AM</option>
															<option value="08:00 AM" <?php echo ("08:00 AM" == $closing_hour)?'selected':'' ;?>>08:00 AM</option>
															<option value="09:00 AM" <?php echo ("09:00 AM" == $closing_hour)?'selected':'' ;?>>09:00 AM</option>
															<option value="10:00 AM" <?php echo ("10:00 AM" == $closing_hour)?'selected':'' ;?>>10:00 AM</option>
															<option value="11:00 AM" <?php echo ("11:00 AM" == $closing_hour)?'selected':'' ;?>>11:00 AM</option>
															<option value="12:00 PM" <?php echo ("12:00 AM" == $closing_hour)?'selected':'' ;?>>12:00 PM</option>
															<option value="01:00 PM" <?php echo ("01:00 PM" == $closing_hour)?'selected':'' ;?>>01:00 PM</option>
															<option value="02:00 PM" <?php echo ("02:00 PM" == $closing_hour)?'selected':'' ;?>>02:00 PM</option>
															<option value="03:00 PM" <?php echo ("03:00 PM" == $closing_hour)?'selected':'' ;?>>03:00 PM</option>
															<option value="04:00 PM" <?php echo ("04:00 PM" == $closing_hour)?'selected':'' ;?>>04:00 PM</option>
															<option value="05:00 PM" <?php echo ("05:00 PM" == $closing_hour)?'selected':'' ;?>>05:00 PM</option>
															<option value="06:00 PM" <?php echo ("06:00 PM" == $closing_hour)?'selected':'' ;?>>06:00 PM</option>
															<option value="07:00 PM" <?php echo ("07:00 PM" == $closing_hour)?'selected':'' ;?>>07:00 PM</option>
															<option value="08:00 PM" <?php echo ("08:00 PM" == $closing_hour)?'selected':'' ;?>>08:00 PM</option>
															<option value="09:00 PM" <?php echo ("09:00 PM" == $closing_hour)?'selected':'' ;?>>09:00 PM</option>
															<option value="10:00 PM" <?php echo ("10:00 PM" == $closing_hour)?'selected':'' ;?>>10:00 PM</option>
															<option value="11:00 PM" <?php echo ("11:00 PM" == $closing_hour)?'selected':'' ;?>>11:00 PM</option>
															<option value="11:00 PM" <?php echo ("12:00 PM" == $closing_hour)?'selected':'' ;?>>12:00 PM</option>
														</select>
													</div>
												</div>
													<div class="row"> </div>
													<div class="row">
														<div class="input-field col s12">
															<textarea id="summernote" name="description" class="materialize-textarea"><?php echo $description;?></textarea>
															<!--label for="textarea1">Listing Descriptions</label-->
														</div>
													</div>

													<div class="row">
														<div class="input-field col s12">
															<select name="status">
																<option value="" disabled="" selected="">Select Status</option>
																<option <?php echo ($status == 'approved')?'selected':'' ?> value="approved">Approved</option>
																<option <?php echo ($status == 'pending')?'selected':'' ?> value="pending">Pending</option>
															</select>
														</div>
													</div>

													<div class="row">
														<div class="db-v2-list-form-inn-tit">
															<h5>Cover Image <span class="v2-db-form-note">(image size 1350x500):<span></span></span></h5>
														</div>
													</div>
													<div class="row tz-file-upload">
														<div class="file-field input-field" id="replaceWhole">
															<div class="tz-up-btn"> <span>File</span>
																<input type="file" id="file" name="cover_image"> </div>
															<div class="file-path-wrapper db-v2-pg-inp">
																<input class="file-path validate" type="text" id="filename" name="cover_image">
															</div>
															<div id="response">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="db-v2-list-form-inn-tit">
															<h5>Photo Gallery <span class="v2-db-form-note">(upload multiple photos note:size 750x500):<span></span></span></h5>
														</div>
													</div>
													<div class="row tz-file-upload">
														<div class="file-field input-field">
															<div class="tz-up-btn" id="upload-button" > <span>File</span>
															 </div>
															<div class="file-path-wrapper db-v2-pg-inp">
																<input class="file-path validate" type="text">
															</div>
														</div>
													</div>


													<div class="form-row" style="margin-top: 10px;">
														<div id="uploaded">
															<!-- uploaded pics -->
														</div>
													</div>


													<div class="row">
														<div class="input-field col s12 v2-mar-top-40"> <a class="waves-effect waves-light btn-large full-btn" href="#" onclick="document.getElementById('the_form').submit();">Submit Listing</a> </div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
<style>
#preloader {
	display: none;
}
</style>
<?php require_once(__DIR__ . '/../admin/_admin_footer.php'); ?>
<?php
$js_inc = __DIR__ . '/../templates/js/user_js/js-edit-place.php';

if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
<!-- include summernote css/js-->
<link href="<?= $baseurl; ?>/lib/summernote/dist/summernote.css" rel="stylesheet">
<script src="<?= $baseurl; ?>/lib/summernote/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
	$('#summernote').summernote({
		placeholder: "Listing Descriptions",
		height: 450,
		styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
		toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'picture', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			  ]
	});

	// edit page form submitted
    $('#submit').click(function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl; ?>' + '/admin/admin-process-edit-page.php';

		$.post(post_url, {
			params: $('form.form-edit-page').serialize(),
			},
			function(data) {
				$('.edit-page').empty().html(data);

			}
		);
	});
	
	var _URL = window.URL || window.webkitURL;

	$("#file").change(function(e) {
		var file, img;
		if ((file = this.files[0])) {
			img = new Image();
			img.onload = function() {
				if(this.width !== 1350 || this.height != 500) {
					alert("please upload image with mentioned size");
					var file = document.getElementById('file');
  					file.value = '';
					$('#filename').val('');
				}
			};
			img.onerror = function() {
				alert( "not a valid file: " + file.type);
			};
			img.src = _URL.createObjectURL(file);
		}
	});
});
</script>