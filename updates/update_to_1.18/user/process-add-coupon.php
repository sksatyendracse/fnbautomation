<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/user_area_inc.php');
require_once(__DIR__ . '/../vendor/swiftmailer/swiftmailer/lib/swift_required.php');

// csrf check
require_once(__DIR__ . '/_user_inc_request_with_php.php');

/*--------------------------------------------------
init vars
--------------------------------------------------*/
$errors = array();
$amount = 0;

// has errors
$has_errors = false;

// default assume place submitted successfully
$result_message = '';

/*--------------------------------------------------
Process post
--------------------------------------------------*/
if($_SERVER['REQUEST_METHOD'] === 'POST') {

	$coupon_title       = (!empty($_POST['latlng']))             ? $_POST['latlng']             : '';
	$coupon_description = (!empty($_POST['coupon_description'])) ? $_POST['coupon_description'] : '';
	$coupon_expire      = (!empty($_POST['coupon_expire']))      ? $_POST['coupon_expire']      : '';
	$coupon_place_id    = (!empty($_POST['coupon_place_id']))    ? $_POST['coupon_place_id']    : '';

	/*--------------------------------------------------
	prepare vars
	--------------------------------------------------*/
	// trim
	$coupon_title       = trim($coupon_title);
	$coupon_description = trim($coupon_description);
	$coupon_place_id    = trim($coupon_place_id);
	$coupon_expire      = trim($coupon_expire);
	$coupon_img         = trim($coupon_img);

	/*--------------------------------------------------
	validate vars
	--------------------------------------------------*/

	// check if place id is owned by user
	$allowed_places = array();

	$query = "SELECT * FROM places WHERE userid = :userid";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':userid', $userid);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$allowed_places[] = $row['place_id'];
	}

	if(!in_array($coupon_place_id, $allowed_places)) {
		$has_errors = true;
		exit;
	}

	/*--------------------------------------------------
	Submit routine
	--------------------------------------------------*/
	// check if this page is refreshed/reloaded
	// if $_SESSION['submit_token'] and submitted $_POST['submit_token'] match
	// it means that the page has not been reloaded,
	// process insert, then unset $_SESSION['submit_token'],
	// so that if user reloads this page, it doesn't match, so it's not inserted
	$post_token    = (!empty($_POST['submit_token']))   ? $_POST['submit_token']    : 'aaa';
	$session_token = (isset($_SESSION['submit_token'])) ? $_SESSION['submit_token'] : '';

	if($post_token == $session_token) {
		try {
			$conn->beginTransaction();

			$neighborhood_id = (isset($neighborhood_id) && !empty($neighborhood_id)) ? $neighborhood_id : NULL;

			// insert into places table
			$query = "INSERT INTO coupons(
				title,
				description,
				userid,
				place_id,
				expire,
				img
			)
			VALUES(
				:title,
				:description,
				:userid,
				:place_id,
				:expire,
				:img
			)";

			// set valid until value which is just the number of days of the period
			$valid_until = ($plan_period == 0 || $plan_period > 9999) ? 9999 : $plan_period;

			$stmt = $conn->prepare($query);
			$stmt->bindValue(':title', $title);
			$stmt->bindValue(':description', $description);
			$stmt->bindValue(':userid', $userid);
			$stmt->bindValue(':place_id', $place_id);
			$stmt->bindValue(':expire', $expire);
			$stmt->bindValue(':img', $img);
			$stmt->execute();

			// photos

			// delete pics from temp folder that were deleted by user while posting
			if(!empty($delete_temp_pics)) {
				foreach($delete_temp_pics as $v) {
					$temp_pic_path = $pic_basepath . '/' . $place_tmp_folder . '/' . $v;
					if(is_file($temp_pic_path)) {
						unlink($temp_pic_path);
					}
				}
			}

			// uploaded images
			if(!empty($uploads)) {
				// define dirs
				$query = "SELECT photo_id FROM photos ORDER BY photo_id DESC LIMIT 1";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$last_photo_id = $row['photo_id'];
				$dir_num = floor($last_photo_id / 1000) + 1;

				$dir_full  = $pic_basepath . '/' . $place_full_folder . '/' . $dir_num;
				$dir_thumb = $pic_basepath . '/' . $place_thumb_folder . '/' . $dir_num;

				if (!is_dir($dir_full)) {
					mkdir($dir_full, 0777, true);
				}

				if (!is_dir($dir_thumb)) {
					mkdir($dir_thumb, 0777, true);
				}

				// tmp folder
				$tmp_folder = $pic_basepath . '/' . $place_tmp_folder;

				if(!isset($global_thumb_width)) {
					$global_thumb_width = 250;
				}
				if(!isset($global_thumb_height)) {
					$global_thumb_height = 250;
				}

				foreach($uploads as $v) {
					// only insert into db if the move from temp to final destination folder is successful,
					// otherwise user could send custom uploads[] value and replace original(thus deleting) previous pics
					// from other ads
					$tmp_file = $tmp_folder . '/' . $v;

					if(copy($tmp_file, $dir_full . '/' . $v)) {
						// insert into photos table
						$stmt = $conn->prepare('
						INSERT INTO photos(place_id, dir, filename)
						VALUES(:place_id, :dir, :filename)');

						$stmt->bindValue(':place_id', $place_id);
						$stmt->bindValue(':dir'     , $dir_num);
						$stmt->bindValue(':filename', $v);
						$stmt->execute();
					}

					smart_resize_image($tmp_file, null, $global_thumb_width, $global_thumb_height, false, $dir_thumb . '/' . $v, true, false, 85);

					// delete pic from tmp_photos table
					$query = "DELETE FROM tmp_photos WHERE filename = :filename";
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':filename', $v);
					$stmt->execute();
				}
			} // end if(!empty($uploads))

			$conn->commit();
			$has_errors = false;
			$txt_main_title = $txt_main_title_success;
			$result_message = $txt_checkout_msg;
		} // end try block
		catch(PDOException $e) {
			$conn->rollBack();
			$has_errors = true;
			$txt_main_title = $txt_main_title_error;
			$result_message = $e->getMessage();
		}

		// empty session submit token
		unset($_SESSION['submit_token']);
	} // end if($post_token == $session_token)

	else { // else probably user reloaded page
		$has_errors = false; // false so the paypal button is shown
		$txt_main_title = $txt_main_title_success;
		$result_message = $txt_checkout_msg;
	}

	// thanks messages
	$txt_thanks = (!$is_admin) ? $txt_thanks_msg : $txt_thanks_admin;

	// place id, in case page is refreshed, $conn->lastInsertId() is lost, so get place_id from SESSION
	if(empty($place_id) && isset($_SESSION['last_submitted_place_id'])) {
		$place_id = $_SESSION['last_submitted_place_id'];
	}
}
