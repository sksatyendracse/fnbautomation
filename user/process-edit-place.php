<?php
session_start();
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/../vendor/swiftmailer/swiftmailer/lib/swift_required.php');

// csrf check
//require_once(__DIR__ . '/_user_inc_request_with_php.php');

/*--------------------------------------------------
init vars
--------------------------------------------------*/
$errors = array();

// initialize swiftmailer
$transport_smtp = (new Swift_SmtpTransport($smtp_server, $smtp_port, $cfg_smtp_encryption))
	->setUsername($smtp_user)
	->setPassword($smtp_pass);

$mailer = new Swift_Mailer($transport_smtp);

/*--------------------------------------------------
Post vars
--------------------------------------------------*/

// POST vars
$place_id             = $_POST['place_id'];
$latlng               = (!empty($_POST['latlng']))               ? $_POST['latlng']               : '';
$place_name           = (!empty($_POST['place_name']))           ? $_POST['place_name']           : '';
$address              = (!empty($_POST['address']))              ? $_POST['address']              : '';
$postal_code          = (!empty($_POST['postal_code']))          ? $_POST['postal_code']          : '';
$cross_street         = (!empty($_POST['cross_street']))         ? $_POST['cross_street']         : '';
$neighborhood_name    = (!empty($_POST['neighborhood_name']))    ? $_POST['neighborhood_name']    : '';
$city_id              = (!empty($_POST['city_id']))              ? $_POST['city_id']              : 0;
$inside               = (!empty($_POST['inside']))               ? $_POST['inside']               : '';
$area_code            = (!empty($_POST['area_code']))            ? $_POST['area_code']            : 0;
$phone                = (!empty($_POST['phone']))                ? $_POST['phone']                : '';
$email                = (!empty($_POST['email']))                ? $_POST['email']                : '';
$twitter              = (!empty($_POST['twitter']))              ? $_POST['twitter']              : '';
$facebook             = (!empty($_POST['facebook']))             ? $_POST['facebook']             : '';
$foursq_id            = (!empty($_POST['foursq_id']))            ? $_POST['foursq_id']            : '';
$website              = (!empty($_POST['website']))              ? $_POST['website']              : '';
$description          = (!empty($_POST['description']))          ? $_POST['description']          : '';
$category_id          = (!empty($_POST['category_id']))          ? $_POST['category_id']          : '';
$business_hours       = (!empty($_POST['business_hours']))       ? $_POST['business_hours']       : array();
$business_hours_info  = (!empty($_POST['business_hours_info']))  ? $_POST['business_hours_info']  : '';
$uploads              = (!empty($_POST['uploads']))              ? $_POST['uploads']              : array();
$existing_pics        = (!empty($_POST['existing_pics']))        ? $_POST['existing_pics']        : array();
$delete_existing_pics = (!empty($_POST['delete_existing_pics'])) ? $_POST['delete_existing_pics'] : array();
$delete_temp_pics     = (!empty($_POST['delete_temp_pics']))     ? $_POST['delete_temp_pics']     : array();
$custom_fields_ids    = (!empty($_POST['custom_fields_ids']))    ? $_POST['custom_fields_ids']    : '';
$opening_day   = (!empty($_POST['opening_day']  )) ? $_POST['opening_day']   : '';
$opening_hour   = (!empty($_POST['opening_hour']  )) ? $_POST['opening_hour']   : '';
$closing_hour   = (!empty($_POST['closing_hour']  )) ? $_POST['closing_hour']   : '';
$status = (!empty($_POST['status']  )) ? $_POST['status']   : '';

// initial status
if($status == "pending"){
	$paid   = 0;
} else {
	$paid = 1;
}

// check user id who submitted this place
$query = "SELECT userid FROM places WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$place_userid = $row['userid'];

// check if user has permission to edit this place
if($place_userid != $userid && 0) {
	// logged in userid is different from this place's userid
	// maybe it's an admin
	if(!$is_admin) {
		die('no permission to edit ' . $place_name);
	}
}

/*--------------------------------------------------
prepare vars
--------------------------------------------------*/
// lat/lng
if(!empty($latlng)) {
	$latlng = str_replace('(', '', $latlng);
	$latlng = str_replace(')', '', $latlng);
	$latlng = explode(',', $latlng);

	$lat = isset($latlng[0]) ? trim($latlng[0]) : null;
	$lng = isset($latlng[1]) ? trim($latlng[1]) : null;

	if(!empty($lat)) {
		settype($lat, 'float');
	}

	if(!empty($lng)) {
		settype($lng, 'float');
	}
}

else {
	$lat = null;
	$lng = null;
}

// clean phone
$area_code = preg_replace("/[^0-9]/", "", $area_code);
$phone = preg_replace("/[^0-9]/", "", $phone);

// normalize twitter url
$twitter = twitter_url(trim($twitter));

// normalize facebook url
$facebook = facebook_url(trim($facebook));

// clean and normalize website url
$website = site_url(trim($website));

// check valid foursquare id
if(!validate_username($foursq_id)) {
	$errors[] = 'Invalid Foursquare Id';
	$foursq_id = '';
}

// find state id
if($city_id > 0) {
	$query = "SELECT state_id FROM cities WHERE city_id = :city_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $city_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$state_id = $row['state_id'];
}
else {
	$state_id = 0;
}


// get array of existing photos associated with this place, so it's possible to check ownership later
$existing_pics_in_db = array();
$query = "SELECT * FROM photos WHERE place_id = :place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$existing_pics_in_db[] = array('dir' => $row['dir'], 'filename' => $row['filename']);
}

// neighborhood
if(!empty($neighborhood_name)) {
	$neighborhood_slug = to_slug($neighborhood_name);

	$query = "SELECT * FROM neighborhoods
				WHERE neighborhood_slug = :neighborhood_slug AND city_id = :city_id LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':neighborhood_slug', $neighborhood_slug);
	$stmt->bindValue(':city_id', $city_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// this neighborhood in this city already exists
	if($neighborhood_slug == $row['neighborhood_slug']) {
			$neighborhood_id = $row['neighborhood_id'];
	}
	else {
		$query = "INSERT INTO neighborhoods(neighborhood_slug, neighborhood_name, city_id)
					VALUES(:neighborhood_slug, :neighborhood_name, :city_id)";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':neighborhood_slug', $neighborhood_slug);
		$stmt->bindValue(':neighborhood_name', $neighborhood_name);
		$stmt->bindValue(':city_id', $city_id);
		$stmt->execute();

		$neighborhood_id = $conn->lastInsertId();
	}
}

$neighborhood_id = (isset($neighborhood_id)) ? $neighborhood_id : 0;

/*--------------------------------------------------
Custom fields
--------------------------------------------------*/
$custom_fields_ids = explode(',', $custom_fields_ids);
$custom_fields = array();
foreach($custom_fields_ids as $v) {
	$field_key = 'field_' . $v;

	if(!empty($_POST[$field_key])) {
		if(!is_array($_POST[$field_key])) {
			$this_field_value = (!empty($_POST[$field_key])) ? $_POST[$field_key] : '';
		}
		else {
			$this_field_value = (!empty($_POST[$field_key])) ? $_POST[$field_key] : array();
		}

		$custom_fields[] = array(
			'field_id'    => $v,
			'field_value' => $this_field_value);
	}
}


/*--------------------------------------------------
Submit routine
--------------------------------------------------*/
// check if this page is refreshed/reloaded
// if $_SESSION['submit_token'] and submitted $_POST['submit_token'] match
// it means that the page has not been reloaded,
// process insert, then unset $_SESSION['submit_token'],
// so that if user reloads this page, it doesn't match, so it's not inserted
$post_token     = (!empty($_POST['submit_token'])) ? $_POST['submit_token'] : 'aaa';
$session_token  = (isset($_SESSION['submit_token'])) ? $_SESSION['submit_token'] : '';
$result_message = "Information updated successfully.";
if($post_token == $session_token || 1) {
	try {
		$conn->beginTransaction();

		// uploaded image
		$uploaded_img = basename($_FILES['cover_image']['name']);
		// get file extension
		$ext = pathinfo($uploaded_img, PATHINFO_EXTENSION);
		$ext = mb_strtolower($ext);
		// generate filename
		$filename = date('y.m.d.H.i') . "-" . microtime(true) . "-" . mt_rand(0, 99999999) . '.' . $ext;
		$image_dir = str_replace("/user","",__DIR__);
		$place_pic_tmp = $image_dir  . '/admin/images/listing/' . $filename;
		$cover_image = '';
		$place_image = '';
		if(@move_uploaded_file($_FILES['cover_image']['tmp_name'], $place_pic_tmp)) {
			$cover_image = $filename;
			$place_image = ' cover_image = :cover_image, ';
		}

		// update places table
		$query = "UPDATE places SET
			lat                 = :lat,
			lng                 = :lng,
			place_name          = :place_name,
			address             = :address,
			postal_code         = :postal_code,
			cross_street        = :cross_street,
			neighborhood        = :neighborhood,
			city_id             = :city_id,
			state_id            = :state_id,
			inside              = :inside,
			area_code           = :area_code,
			phone               = :phone,
			email               = :email,
			twitter             = :twitter,
			facebook            = :facebook,
			foursq_id           = :foursq_id,
			website             = :website,
			description         = :description,
			business_hours_info = :business_hours_info,".$place_image."
			status	            = :status
			WHERE place_id      = :place_id";
		
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':lat', $lat);
		$stmt->bindValue(':lng', $lng);
		$stmt->bindValue(':place_name', $place_name);
		$stmt->bindValue(':address', $address);
		$stmt->bindValue(':postal_code', $postal_code);
		$stmt->bindValue(':cross_street', $cross_street);
		$stmt->bindValue(':neighborhood', $neighborhood_id);
		$stmt->bindValue(':city_id', $city_id);
		$stmt->bindValue(':state_id', $state_id);
		$stmt->bindValue(':inside', $inside);
		$stmt->bindValue(':area_code', $area_code);
		$stmt->bindValue(':phone', $phone);
		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':twitter', $twitter);
		$stmt->bindValue(':facebook', $facebook);
		$stmt->bindValue(':foursq_id', $foursq_id);
		$stmt->bindValue(':website', $website);
		$stmt->bindValue(':description', $description);
		$stmt->bindValue(':business_hours_info', $business_hours_info);
		if(!empty($cover_image)){
			$stmt->bindValue(':cover_image', $cover_image);
		}
		$stmt->bindValue(':status', $status);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();

		// rel_place_cat

		// first delete all categories for this place_id
		$query = "DELETE FROM rel_place_cat WHERE place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();

		// rel_place_cat
		if(!empty($category_id)) {
			$query = "DELETE FROM rel_place_cat WHERE place_id = :place_id";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':place_id', $place_id);
			$stmt->execute();

			$rows = [];
			$listing_categories = $_POST['category_id'];
			foreach($listing_categories as $cat_id) {
				$temp['place_id'] = $place_id;
				$temp['cat_id'] = $cat_id;
				$temp['city_id'] = $city_id;
				$sql = "INSERT INTO rel_place_cat(place_id, cat_id, city_id)
				VALUES(:place_id, :cat_id, :city_id)";
				$stmt= $conn->prepare($sql);
				try{
					$stmt->execute($temp);
				}catch(Exception $e) {
					echo 'Message: ' .$e->getMessage();
				}			
			}
			
		}

		if(!empty($opening_day)) {

			$query = "DELETE FROM business_hours WHERE place_id = :place_id";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(':place_id', $place_id);
			$stmt->execute();

			$rows = [];
			$days = $_POST['opening_day'];
			foreach($days as $day) {
				$dayvar['place_id'] = $place_id;
				$dayvar['day'] = $day;
				$sql = "INSERT INTO business_hours(place_id, day)	VALUES(:place_id, :day)";
				$stmt= $conn->prepare($sql);
				try{
					$stmt->execute($dayvar);
				}catch(Exception $e) {
					echo 'Message: ' .$e->getMessage();
				}			
			}
		}

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

		// delete $delete_existing_pics
		// user deletes pictures that were previously submitted when adding or editing place before
		// $existing_pics_in_db[] = array('dir' => $row['dir'], 'filename' => $row['filename']);
		if(!empty($delete_existing_pics)) {
			$where_clause = '';
			foreach($delete_existing_pics as $k => $v) {
				if(in_array($v, array_column($existing_pics_in_db, 'filename'))) {
					$key = array_search($v, array_column($existing_pics_in_db, 'filename'));
					$dir = $existing_pics_in_db[$key]['dir'];
					$pic_full = $pic_basepath . '/' . $place_full_folder . '/' . $dir . '/' . $v;
					$pic_thumb = $pic_basepath . '/' . $place_thumb_folder . '/' . $dir . '/' . $v;

					if(is_file($pic_full)) {
						unlink($pic_full);
					}
					if(is_file($pic_thumb)) {
						unlink($pic_thumb);
					}

					// delete existing pics from db
					$query = "DELETE FROM photos WHERE filename = :filename";
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':filename', $v);
					$stmt->execute();
				}
			} // end foreach($delete_existing_pics as $k => $v)
		} // end if(!empty($delete_existing_pics))

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

			foreach($uploads as $k => $v) {
				// only insert into db if the move from temp to final destination folder is successful,
				// otherwise user could send custom uploads[] value and replace original(thus deleting) previous pics
				// from other ads
				$tmp_file = $tmp_folder . '/' . $v;

				if(copy($tmp_file, $dir_full . '/' . $v)) {
					// insert into db
					$stmt = $conn->prepare('
					INSERT INTO photos(
						place_id,
						dir,
						filename
						)
					VALUES(
						:place_id,
						:dir,
						:filename
						)
					');

					$stmt->bindValue(':place_id', $place_id);
					$stmt->bindValue(':dir', $dir_num);
					$stmt->bindValue(':filename', $v);
					$stmt->execute();
				}

				smart_resize_image($tmp_file, null, 250, 250, false, $dir_thumb . '/' . $v, true, false, 85);

				// now delete entries in tmp_photos table
				$query = "DELETE FROM tmp_photos WHERE filename = :filename";
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':filename', $v);
				$stmt->execute();
			}
		} // end if(!empty($uploads))

		// custom fields
		$query = "DELETE FROM rel_place_custom_fields WHERE place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->execute();

		foreach($custom_fields as $v) {
			if(!is_array($v['field_value'])) {
				if(!empty($v['field_value'])) {
					$query = "INSERT INTO rel_place_custom_fields(place_id, field_id, field_value)
						VALUES(:place_id, :field_id, :field_value)";
					$stmt = $conn->prepare($query);
					$stmt->bindValue(':place_id', $place_id);
					$stmt->bindValue(':field_id', $v['field_id']);
					$stmt->bindValue(':field_value', $v['field_value']);
					$stmt->execute();
				}
			}
			else {
				foreach($v['field_value'] as $v2) {
					if(!empty($v2)) {
						$query = "INSERT INTO rel_place_custom_fields(place_id, field_id, field_value)
							VALUES(:place_id, :field_id, :field_value)";
						$stmt = $conn->prepare($query);
						$stmt->bindValue(':place_id', $place_id);
						$stmt->bindValue(':field_id', $v['field_id']);
						$stmt->bindValue(':field_value', $v2);
						$stmt->execute();
					}
				}
			}
		}

		$conn->commit();
		$result_message = $txt_success;
	} // end try block ()
	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();
	}

	// empty session submit token
	unset($_SESSION['submit_token']);
} // end if($post_token == $session_token)

// translation replacements
$result_message = str_replace('%place_name%', $place_name, $result_message);
$txt_main_title = str_replace('%place_name%', $place_name, $txt_main_title);

/*--------------------------------------------------
email admin
--------------------------------------------------*/
if(!empty($mail_after_post)) {
	// initialize swiftmailer
	$transport_smtp = (new Swift_SmtpTransport($smtp_server, $smtp_port, $cfg_smtp_encryption))
		->setUsername($smtp_user)
		->setPassword($smtp_pass);

	$mailer = new Swift_Mailer($transport_smtp);

	$query = "SELECT * FROM email_templates WHERE type = 'process_edit_place'";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$email_subject = $row['subject'];
	$email_body = $row['body'];

	$message = (new Swift_Message())
		->setSubject($email_subject)
		->setFrom(array($admin_email => $site_name))
		->setTo($admin_email)
		->setBody($email_body)
		->setReplyTo($admin_email)
		->setReturnPath($admin_email)
		;

	$mailer->send($message);
}
//$location = $baseurl."/admin/admin-listings";
//header("Location: $location");
?>
<script>window.location="<?php echo $baseurl;?>/admin/admin-listings";</script>