<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/../inc/smart_resize_image.php');
require_once(__DIR__ . '/../inc/general-functions.php');
//require_once(__DIR__ . '/user_area_inc.php');

// only allow access to this file for logged in users
if(!array_key_exists('userid', $_SESSION) && empty($_SESSION['userid'])) {
    die('You do not have permission to access this page');
}

// user details
$userid = $_SESSION['userid'];

// max size
$upload_max_filesize = ini_get('upload_max_filesize');

if($_FILES['coupon_img']['error'] != 0 || !exif_imagetype($_FILES['coupon_img']['tmp_name'])) {
	$response = array(
		'result' => 'fail',
		'message' => file_upload_errors($_FILES['coupon_img']['error']),
		'filename' => ''
	);

	echo json_encode($response);
	exit();
}
else {
	// basename - Returns trailing name component of path
	$uploaded_img = basename($_FILES['coupon_img']['name']);

	// get file extension
	$extension = pathinfo($uploaded_img, PATHINFO_EXTENSION);
	$extension = mb_strtolower($extension);

	// generate file name
	$filename = uniqid();

	// generate folder
	$folder = substr($filename, 0, 2);

	// paths
	$filename = $filename . '.' . $extension;
	$path_tmp = $pic_basepath . '/coupons-tmp/' . $filename;
	$url_tmp  = $pic_baseurl . '/coupons-tmp/' . $filename;

	// move uploaded
	if(@move_uploaded_file($_FILES['coupon_img']['tmp_name'], $path_tmp)) {
		$response = array(
			'result' => 'success',
			'message' => $url_tmp,
			'filename' => $filename,
		);
	}

	else {
		$response = array(
			'result' => 'fail',
			'message' => 'move_uploaded_file error',
			'filename' => ''
		);
	}

	echo json_encode($response);
}