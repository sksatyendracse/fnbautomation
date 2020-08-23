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
$result_message = '';
$has_errors = false;

/*--------------------------------------------------
Process post
--------------------------------------------------*/
if($_SERVER['REQUEST_METHOD'] === 'POST') {

	$coupon_title       = (!empty($_POST['coupon_title'      ])) ? $_POST['coupon_title'      ] : '';
	$coupon_description = (!empty($_POST['coupon_description'])) ? $_POST['coupon_description'] : '';
	$uploaded_img       = (!empty($_POST['uploaded_img'      ])) ? $_POST['uploaded_img'      ] : '';
	$coupon_expire      = (!empty($_POST['coupon_expire'     ])) ? $_POST['coupon_expire'     ] : '';
	$coupon_place_id    = (!empty($_POST['coupon_place_id'   ])) ? $_POST['coupon_place_id'   ] : '';

	/*--------------------------------------------------
	prepare vars
	--------------------------------------------------*/
	// trim
	$coupon_title       = trim($coupon_title);
	$coupon_description = trim($coupon_description);
	$uploaded_img       = trim($uploaded_img);
	$coupon_expire      = trim($coupon_expire);
	$coupon_place_id    = trim($coupon_place_id);

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
	try {
		$conn->beginTransaction();

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

		$stmt = $conn->prepare($query);
		$stmt->bindValue(':title', $coupon_title);
		$stmt->bindValue(':description', $coupon_description);
		$stmt->bindValue(':userid', $userid);
		$stmt->bindValue(':place_id', $coupon_place_id);
		$stmt->bindValue(':expire', $coupon_expire);
		$stmt->bindValue(':img', $uploaded_img);
		$stmt->execute();

		/*--------------------------------------------------
		Photos
		--------------------------------------------------*/

		// delete pics from temp folder that were deleted by user while posting

		// move coupon img to main folder

		// folder
		$folder_path = $pic_basepath . '/coupons/' . substr($uploaded_img, 0, 2);

		if (!is_dir($folder_path)) {
			if(!mkdir($folder_path, 0755, true)) {
				$has_errors = true;
				$result_message = 'Error creating directory';
			}
		}

		// paths and folders
		$path_tmp   = $pic_basepath . '/coupons-tmp/' . $uploaded_img;
		$path_final = $folder_path . '/' . $uploaded_img;

		if(is_file($path_tmp)) {
			if(copy($path_tmp, $path_final)) {

				/**
				* easy image resize function
				* @param  $file - file name to resize
				* @param  $string - The image data, as a string
				* @param  $width - new image width
				* @param  $height - new image height
				* @param  $proportional - keep image proportional, default is no
				* @param  $output - name of the new file (include path if needed)
				* @param  $delete_original - if true the original image will be deleted
				* @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
				* @param  $quality - enter 1-100 (100 is best quality) default is 100
				* @return boolean|resource
				*/

				//smart_resize_image($path_final, null, $coupon_size[0], $coupon_size[1], false, 'file', true, false, 85);

				unlink($path_tmp);
			}
		}

		/*--------------------------------------------------
		Commit
		--------------------------------------------------*/
		$conn->commit();
		$has_errors = false;
	} // end try block

	catch(PDOException $e) {
		$conn->rollBack();
		$has_errors = true;
		$result_message = $e->getMessage();

	}
}

if($has_errors === false) {
	header("Location: $baseurl/user/my-coupons.php");
}

else {
	echo $result_message;
}