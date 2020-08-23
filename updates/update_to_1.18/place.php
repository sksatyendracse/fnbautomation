<?php
require_once(__DIR__ . '/inc/config.php');
// example.com/place/[place_id]/central-park
# 3)
# http://www.example.com/city-name/place/2537-place-slug
# must rewrite to
# http://www.example.com/place/2537 // 2537 is the place_id
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
$place_id = $frags[1];

// check if place id is numeric
if(is_numeric($place_id)) {
	require_once 'place_db.php';
}

else {
	throw new Exception('Place id must be numeric');
}

// $cats_arr is created in place_db.php
if(!empty($cats_arr)) {
	foreach($cats_arr as $k => $v) {
		$cat_id = $v['id'];

		$query = "INSERT IGNORE INTO rel_place_cat(
			place_id,
			cat_id,
			city_id)
		VALUES(
			:place_id,
			:cat_id,
			:city_id)";

		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $place_id);
		$stmt->bindValue(':cat_id', $cat_id);
		$stmt->bindValue(':city_id', $city_id);
		$stmt->execute();
	}
}

// v.1.11
// contact form
$query = "SELECT * FROM config WHERE property = :plugin_contact_owner";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':plugin_contact_owner', 'plugin_contact_owner');
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$plugin_values = $row['value'];
$plugin_values = unserialize($plugin_values);

// v.1.11
// coupons
$query = "SELECT * FROM coupons WHERE place_id = :place_id";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
// if this place has coupons
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

// template file
if(!$place_404) {
	// html title
	$txt_html_title = str_replace('%place_name%', $place_name, $txt_html_title);
	$txt_html_title = str_replace('%cat_name%'  , $cat_name  , $txt_html_title);
	$txt_html_title = str_replace('%city_name%' , $city_name , $txt_html_title);
	$txt_html_title = str_replace('%state_abbr%', $state_abbr, $txt_html_title);
	$txt_meta_desc  = str_replace('%place_name%', $place_name, $txt_meta_desc);
	$txt_meta_desc  = str_replace('%cat_name%'  , $cat_name  , $txt_meta_desc);
	$txt_meta_desc  = str_replace('%city_name%' , $city_name , $txt_meta_desc);
	$txt_meta_desc  = str_replace('%state_abbr%', $state_abbr, $txt_meta_desc);

	// vars
	$phone = str_replace('+1 ', '', $phone);
	$website = (!empty($website)) ?
		'<a href="' . $website . '" rel="nofollow" target="_blank">' . $website . '</a>'
		: '';
	$place_slug = to_slug($place_name);

	// canonical url
	$canonical = '';
	if(!empty($city_slug)) {
		$canonical = "$baseurl/$city_slug/place/$place_id/$place_slug";
	}
	else {
		$canonical = "$baseurl/$default_country_code/place/$place_id/$place_slug";
	}

	$txt_about = str_replace('%place_name%', $place_name, $txt_about);
	$txt_about = str_replace('%cat_name%', $cat_name, $txt_about);

	if(!empty($city_name)) {
		$txt_about = str_replace('%city_name%', $city_name, $txt_about);
	}

	elseif(!empty($county_name)) {
		$txt_about = str_replace('%city_name%', $county_name, $txt_about);
	}

	else {
		$txt_about = str_replace('%city_name%', '', $txt_about);
	}

	if(!empty($state_abbr)) {
		$txt_about = str_replace('%state_abbr%', $state_abbr, $txt_about);
	}

	else {
		$txt_about = str_replace('%state_abbr%', $country_name, $txt_about);
	}

	// translation substitutions
	$txt_reviews = str_replace('%place_name%', $place_name, $txt_reviews);

	// plugins: custom fields
	$display_custom_fields = '';
	if(file_exists(__DIR__ . '/plugins/custom_fields/user-custom-fields-place.php')) {
		include_once __DIR__ . '/plugins/custom_fields/user-custom-fields-place.php';
	}

	// template
	if(is_file(__DIR__ . '/templates/my_tpl_place.php')) {
		require_once(__DIR__ . '/templates/my_tpl_place.php');
	}

	else {
		require_once __DIR__ . '/templates/tpl_place.php';
	}
}

else {
	header("HTTP/1.0 404 Not Found");
	require_once __DIR__ . '/templates/tpl_place_404.php';
}

// v.1.11 new language vars
if(!isset($txt_overview                    )) $txt_overview                     = "Overview";
if(!isset($txt_photos                      )) $txt_photos                       = "Photos";
if(!isset($txt_coupons                     )) $txt_coupons                      = "Coupons";
if(!isset($txt_reviews                     )) $txt_reviews                      = "Reviews";
if(!isset($txt_contact                     )) $txt_contact                      = "Contact";
if(!isset($txt_phone                       )) $txt_phone                        = "Phone";
if(!isset($txt_website                     )) $txt_website                      = "Website";
if(!isset($txt_claim                       )) $txt_claim                        = "Claim Listing";
if(!isset($txt_view_details                )) $txt_view_details                 = "View Details";
if(!isset($txt_contact_owner_btn_contact   )) $txt_contact_owner_btn_contact    = "contact";
if(!isset($txt_contact_owner_label_email   )) $txt_contact_owner_label_email    = "Your email";
if(!isset($txt_contact_owner_label_msg     )) $txt_contact_owner_label_msg      = "Message";
if(!isset($txt_contact_owner_msg_sent      )) $txt_contact_owner_msg_sent       = "Message sent";
if(!isset($txt_contact_owner_invalid_email )) $txt_contact_owner_invalid_email  = "Invalid email";
if(!isset($txt_contact_owner_invalid_answer)) $txt_contact_owner_invalid_answer = "Invalid answer";
if(!isset($txt_contact_owner_please_wait   )) $txt_contact_owner_please_wait    = "Please wait a few seconds before submitting again";