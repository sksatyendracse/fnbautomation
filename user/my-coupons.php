<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/user_area_inc.php');

// init
$coupons_arr = array();
$coupon_create = false;

// check if user has listings
$user_has_listings = false;
$user_places = array();

$query = "SELECT * FROM places WHERE userid = :userid";
$stmt = $conn->prepare($query);
$stmt->bindValue(':userid', $userid);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$place_id   = (!empty($row['place_id']))   ? $row['place_id']   : '';
	$place_name = (!empty($row['place_name'])) ? $row['place_name'] : '';

	$cur_loop_arr = array(
					'place_id' => $place_id,
					'place_name' => $place_name,
					);

	$user_places[] = $cur_loop_arr;
}

if(!empty($user_places)) {
	$user_has_listings = true;
}

/*--------------------------------------------------
Create coupon form
--------------------------------------------------*/
if(isset($_GET['event']) && $_GET['event'] == 'create') {
	if($user_has_listings == true) {
		$coupon_create = true;
	}
}

/*--------------------------------------------------
Delete coupon
--------------------------------------------------*/
else if(isset($_GET['event']) && $_GET['event'] == 'delete') {
	if(isset($_GET['coupon'])) {
		// remove coupon image
		$query = "SELECT * FROM coupons WHERE id = :id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':id', $_GET['coupon']);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$coupon_img = !empty($row['img']) ? $row['img'] : '';

		if(!empty($coupon_img)) {
			$coupon_img = $pic_basepath . '/coupons/' . substr($coupon_img, 0, 2) . '/' . $coupon_img;
			unlink($coupon_img);
		}

		// delete coupon
		$query = "DELETE FROM coupons WHERE id = :id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':id', $_GET['coupon']);
		$stmt->execute();

		header("Location: $baseurl/user/my-coupons.php");
	}
}

/*--------------------------------------------------
Show coupons
--------------------------------------------------*/
else {
	// get coupons for this user
	$query = "SELECT * FROM coupons WHERE userid = :userid";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':userid', $userid);
	$stmt->execute();

	// if this user has coupons
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$coupon_id          = (!empty($row['id'         ])) ? $row['id'         ] : '';
		$coupon_title       = (!empty($row['title'      ])) ? $row['title'      ] : '';
		$coupon_description = (!empty($row['description'])) ? $row['description'] : '';
		$coupon_place_id    = (!empty($row['place_id'   ])) ? $row['place_id'   ] : '';
		$coupon_expire      = (!empty($row['expire'     ])) ? $row['expire'     ] : '';
		$coupon_img         = (!empty($row['img'        ])) ? $row['img'        ] : '';

		// photo_url
		$coupon_img_url = '';
		if(!empty($coupon_img)) {
			$coupon_img_url = $baseurl . '/pictures/coupons/' . substr($coupon_img, 0, 2) . '/' . $coupon_img;
		}
		else {
			$coupon_img_url = $baseurl . '/imgs/blank.png';
		}

		// description
		if(!empty($coupon_description)) {
			$coupon_description = mb_substr($coupon_description, 0, 75) . '...';
		}

		// sanitize
		$coupon_title = e($coupon_title);
		$coupon_description = e($coupon_description);

		$cur_loop_arr = array(
						'coupon_id'          => $coupon_id,
						'coupon_title'       => $coupon_title,
						'coupon_description' => $coupon_description,
						'coupon_place_id'    => $coupon_place_id,
						'coupon_expire'      => $coupon_expire,
						'coupon_img'         => $coupon_img_url
						);

		// add cur loop to places array
		$coupons_arr[] = $cur_loop_arr;
	} // end while
}

// template file
if(is_file(__DIR__ . '/templates/user_templates/my_tpl_my-coupons.php')) {
	require_once(__DIR__ . '/templates/user_templates/my_tpl_my-coupons.php');
}

else {
	require_once(__DIR__ . '/../templates/user_templates/tpl_my-coupons.php');
}