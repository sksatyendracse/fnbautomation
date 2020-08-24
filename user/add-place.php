<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');
require_once(__DIR__ . '/../admin/_admin_inc.php'); // checks session and user id
require_once($lang_folder . '/admin_translations/trans-index.php');
require_once(__DIR__ . '/../admin/_admin_header.php');

// check if plan selected
$frags = '';
$plan_id = '';
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
$frags   = explode("/", $frags);
$plan_id = (!empty($frags[1])) ? $frags[1] : '';

if(empty($plan_id)) {
	trigger_error("Invalid plan selection", E_USER_ERROR);
	die();
}

/*--------------------------------------------------
session to prevent multiple form submissions
--------------------------------------------------*/
$submit_token = uniqid('', true);
$_SESSION['submit_token'] = $submit_token;
?>
<script>
localStorage.setItem("submit_token", "<?php echo $submit_token;?>")
</script>
<?php
/*--------------------------------------------------
plugins
--------------------------------------------------*/


/*--------------------------------------------------
translations
--------------------------------------------------*/
$txt_html_title = str_replace('%site_name%', $site_name, $txt_html_title);
$txt_main_title = str_replace('%site_name%', $site_name, $txt_main_title);

?>

<div class="sb2-2">
				<!--== breadcrumbs ==-->
				<div class="sb2-2-2">
					<ul>
						<li><a href="<?= $baseurl; ?>/admin/"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
						<li class="active-bre"><a href="#"> Add Listing</a> </li>
						<li class="page-back"><a href="<?= $baseurl; ?>/admin/"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>Add New Listing</h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-inn-page">
									<div class="tab-inn ad-tab-inn">
										<div class="hom-cre-acc-left hom-cre-acc-right">
											<div class="">
											<form method="post" id="the_form" action="<?= $baseurl; ?>/user/process-add-place.php" enctype="multipart/form-data">
											<input type="hidden" id="submit_token" name="submit_token" value="<?= $_SESSION['submit_token']; ?>">
											<input type="hidden" name="csrf_token" value="<?= session_id(); ?>">
											<input type="hidden" id="latlng" name="latlng">
											<input type="hidden" id="plan_id" name="plan_id" value="2">

													
													<div class="row">
														<div class="input-field col s12">
															<input id="list_name" type="text" class="validate" name="place_name">
															<label for="list_name">Listing Title</label>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12">
															<input id="list_phone" type="text" class="validate"  name="phone">
															<label for="list_phone">Phone</label>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12">
															<input id="email" type="email" class="validate" name="email">
															<label for="email">Email</label>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12">
															<input id="list_addr" type="text" class="validate" name="address">
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
																		<option value="<?= $row['city_id'] ?>"><?= $row['city_name'] ?> <?= $row['state'] ?></option>
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
																<?php get_children(0, 0, 0, $conn); ?>
															</select>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12">
															<select multiple="" name="opening_day[]">
																<option value="" disabled="" selected="">Opening Days</option>
																<option value="7">All Days</option>
																<option value="0">Monday</option>
																<option value="1">Tuesday</option>
																<option value="2">Wednesday</option>
																<option value="3">Thursday</option>
																<option value="4">Friday</option>
																<option value="5">Saturday</option>
																<option value="6">Sunday</option>
															</select>
														</div>
													</div>
												<div class="row">
													<div class="input-field col s6">
														<select name="opening_hour">
															<option value="" disabled="" selected="">Open Time</option>
															<option value="12:00 AM">12:00 AM</option>
															<option value="01:00 AM">01:00 AM</option>
															<option value="02:00 AM">02:00 AM</option>
															<option value="03:00 AM">03:00 AM</option>
															<option value="04:00 AM">04:00 AM</option>
															<option value="05:00 AM">05:00 AM</option>
															<option value="06:00 AM">06:00 AM</option>
															<option value="07:00 AM">07:00 AM</option>
															<option value="08:00 AM">08:00 AM</option>
															<option value="09:00 AM">09:00 AM</option>
															<option value="10:00 AM">10:00 AM</option>
															<option value="11:00 AM">11:00 AM</option>
															<option value="12:00 AM">12:00 PM</option>
															<option value="01:00 PM">01:00 PM</option>
															<option value="02:00 PM">02:00 PM</option>
															<option value="03:00 PM">03:00 PM</option>
															<option value="04:00 PM">04:00 PM</option>
															<option value="05:00 PM">05:00 PM</option>
															<option value="06:00 PM">06:00 PM</option>
															<option value="07:00 PM">07:00 PM</option>
															<option value="08:00 PM">08:00 PM</option>
															<option value="09:00 PM">09:00 PM</option>
															<option value="10:00 PM">10:00 PM</option>
															<option value="11:00 PM">11:00 PM</option>
														</select>
													</div>
													<div class="input-field col s6">
														<select name="closing_hour">
															<option value="" disabled="" selected="">Closing Time</option>
															<option value="12:00 AM">12:00 AM</option>
															<option value="01:00 AM">01:00 AM</option>
															<option value="02:00 AM">02:00 AM</option>
															<option value="03:00 AM">03:00 AM</option>
															<option value="04:00 AM">04:00 AM</option>
															<option value="05:00 AM">05:00 AM</option>
															<option value="06:00 AM">06:00 AM</option>
															<option value="07:00 AM">07:00 AM</option>
															<option value="08:00 AM">08:00 AM</option>
															<option value="09:00 AM">09:00 AM</option>
															<option value="10:00 AM">10:00 AM</option>
															<option value="11:00 AM">11:00 AM</option>
															<option value="12:00 PM">12:00 PM</option>
															<option value="01:00 PM">01:00 PM</option>
															<option value="02:00 PM">02:00 PM</option>
															<option value="03:00 PM">03:00 PM</option>
															<option value="04:00 PM">04:00 PM</option>
															<option value="05:00 PM">05:00 PM</option>
															<option value="06:00 PM">06:00 PM</option>
															<option value="07:00 PM">07:00 PM</option>
															<option value="08:00 PM">08:00 PM</option>
															<option value="09:00 PM">09:00 PM</option>
															<option value="10:00 PM">10:00 PM</option>
															<option value="11:00 PM">11:00 PM</option>
														</select>
													</div>
												</div>
													<div class="row"> </div>
													<div class="row">
														<div class="input-field col s12">
															<textarea id="summernote" name="description" class="materialize-textarea"></textarea>
															<label for="textarea1"></label>
														</div>
													</div>

													<div class="row">
														<div class="input-field col s12">
															<select name="status">
																<option value="" disabled="" selected="">Select Status</option>
																<option value="approved">Approved</option>
																<option value="pending">Pending</option>
															</select>
														</div>
													</div>

													<div class="row">
														<div class="db-v2-list-form-inn-tit">
															<h5>Cover Image <span class="v2-db-form-note">(image size 1350x500):<span></span></span></h5>
														</div>
													</div>
													<div class="row tz-file-upload">
														<div class="file-field input-field">
															<div class="tz-up-btn"> <span>File</span>
																<input type="file" id="file" name="cover_image"> </div>
															<div class="file-path-wrapper db-v2-pg-inp">
																<input class="file-path validate" id="filename" type="text" name="cover_image">
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
$js_inc = __DIR__ . '/../templates/js/user_js/js-add-place.php';

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
