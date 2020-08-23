<?php
require_once(__DIR__ . '/inc/config.php');

// get default values from config.php
if(!isset($city_slug)) {
	$city_slug = $default_city_slug;
}

if(!isset($loc_id)) {
	$loc_id = $default_loc_id;
}

if(!empty($_COOKIE['city_id'])) {
	$loc_id     = !empty($_COOKIE['city_id']) ? $_COOKIE['city_id'] : '';
	$loc_type   = 'c';
	$loc_name   = !empty($_COOKIE['city_name']) ? $_COOKIE['city_name'] : 'City-Name';
	$loc_slug   = !empty($_COOKIE['city_slug']) ? $_COOKIE['city_slug'] : $default_country_code;
	$state_abbr = !empty($_COOKIE['state_abbr']) ? $_COOKIE['state_abbr'] : 'STATE';
	$near_query = urlencode("$loc_name,$state_abbr");
}

else {
	$loc_id     = 0;
	$loc_type   = 'n';
	$loc_name   = '';
	$state_abbr = '';
	$loc_slug   = $default_country_code;
	$near_query = $default_country_code;
}

// main categories
$query = "SELECT * FROM cats WHERE cat_status = 1 AND parent_id = 0 ORDER BY cat_order";
$stmt = $conn->prepare($query);
$stmt->execute();

$main_cats = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$cur_loop = array(
		'cat_id'       => $row['id'],
		'cat_name'     => $row['name'],
		'cat_slug'     => to_slug($row['name']),
		'plural_name'  => $row['plural_name'],
		'iconfont_tag' => $row['iconfont_tag'],
		'cat_order'    => $row['cat_order']);

	$main_cats[] = $cur_loop;
}

/*--------------------------------------------------
Featured listings
--------------------------------------------------*/
$featured_listings = array();

$query = "SELECT
	p.place_id, p.place_name, p.city_id, p.description, p.address, p.feat,
	ph.dir, ph.filename,
	c.city_name, c.slug,
	r.user_id, r.rating, r.text,
	u.first_name, u.last_name
	FROM places p
	LEFT JOIN photos ph ON p.place_id = ph.place_id
	LEFT JOIN cities c ON c.city_id = p.city_id
	LEFT JOIN reviews r ON p.place_id = r.place_id AND r.status = 'approved'
	LEFT JOIN users u ON r.user_id = u.id
	WHERE (p.feat_home = 1 OR (p.feat = 1 AND p.city_id = :city_id)) AND p.status = 'approved'
	GROUP BY p.place_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':city_id', $loc_id);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	// reset $featured_listings array vars
	$feat_place_id               = '';
	$feat_place_name             = '';
	$feat_place_desc             = '';
	$feat_place_slug             = '';
	$feat_photo_url              = '';
	$feat_city_slug              = '';
	$review_user_profile_pic_url = '';
	$feat_username               = '';
	$feat_rating                 = '';
	$feat_review_text            = '';

	// assign vars from query result
	$feat_place_id          = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
	$feat_place_name        = !empty($row['place_name' ]) ? $row['place_name' ] : '';
	$feat_place_desc        = !empty($row['description']) ? $row['description'] : '';
	$feat_city_name         = !empty($row['city_name'  ]) ? $row['city_name'  ] : '';
	$feat_city_slug         = !empty($row['slug'       ]) ? $row['slug'       ] : '';
	$feat_photo_dir         = !empty($row['dir'        ]) ? $row['dir'        ] : '';
	$feat_photo_filename    = !empty($row['filename'   ]) ? $row['filename'   ] : '';
	$feat_review_userid     = !empty($row['user_id'    ]) ? $row['user_id'    ] : '';
	$feat_review_first_name = !empty($row['first_name' ]) ? $row['first_name' ] : '';
	$feat_review_last_name  = !empty($row['last_name'  ]) ? $row['last_name'  ] : '';
	$feat_rating            = !empty($row['rating'     ]) ? $row['rating'     ] : '';
	$feat_review_text       = !empty($row['text'       ]) ? $row['text'       ] : '';

	// sanitize
	$feat_place_name        = e($feat_place_name);
	$feat_place_desc        = e($feat_place_desc);
	$feat_review_first_name = e($feat_review_first_name);
	$feat_review_last_name  = e($feat_review_last_name);
	$feat_review_text       = e($feat_review_text);

	// place name
	$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
	$feat_place_name = str_replace($endash, "-", $feat_place_name);

	// place slug
	$feat_place_slug = to_slug($feat_place_name);

	// limit description text length
	$feat_place_desc = implode(' ', array_slice(explode(' ', $feat_place_desc), 0, 10));

	// feat_photo_url
	$feat_photo_url = '';
	if(!empty($feat_photo_filename)) {
		$feat_photo_url = $pic_baseurl . '/' . $place_full_folder . '/' . $feat_photo_dir . '/' . $feat_photo_filename;
	}

	else {
		$feat_photo_url = $baseurl . '/imgs/empty.png';
	}

	// review with profile pics
	$feat_username = '';

	if(!empty($feat_review_userid)) {
		// user name
		$feat_review_last_name = mb_substr($feat_review_last_name, 0, 1);
		$feat_review_last_name = strtoupper($feat_review_last_name);
		$feat_username = "$feat_review_first_name $feat_review_last_name.";
		$feat_username = trim($feat_username);

		$folder = floor($feat_review_userid / 1000) + 1;
		if(strlen($folder) < 1) {
			$folder = '999';
		}

		$review_user_profile_pic_path = $pic_basepath . '/' . $profile_thumb_folder . '/' . $folder . '/' . $feat_review_userid;
		$review_user_profile_pic_path = glob("$review_user_profile_pic_path.*");

		if(empty($feat_username)) {
			$review_user_display_name = '';
		}

		if(!empty($review_user_profile_pic_path)) {
			$review_user_profile_pic_path     = explode('/', $review_user_profile_pic_path[0]);
			$review_user_profile_pic_filename = end($review_user_profile_pic_path);
			$review_user_profile_pic_url      = "$pic_baseurl/$profile_thumb_folder/$folder/$review_user_profile_pic_filename";
		}
		else {
			$review_user_profile_pic_url = "$baseurl/imgs/blank.png";
		}

		// limit review text
		$feat_review_text = implode(' ', array_slice(explode(' ', $feat_review_text), 0, 10));
	} // end if(!empty($feat_review_userid))

	$feat_place_link = $baseurl . '/' . $feat_city_slug . '/place/' . $feat_place_id . '/' . $feat_place_slug;

	// populate array
	$cur_loop = array(
		'place_id'        => $feat_place_id,
		'place_name'      => $feat_place_name,
		'place_desc'      => $feat_place_desc,
		'place_slug'      => $feat_place_slug,
		'place_link'      => $feat_place_link,
		'photo_url'       => $feat_photo_url,
		'place_city_slug' => $feat_city_slug,
		'profile_pic'     => $review_user_profile_pic_url,
		'user_name'       => $feat_username,
		'rating'          => $feat_rating ,
		'tip_text'        => $feat_review_text
		);

	$featured_listings[] = $cur_loop;
}

/*--------------------------------------------------
Latest listings
--------------------------------------------------*/

$latest_listings = array();
$cfg_latest_listings_count = isset($cfg_latest_listings_count) ? $cfg_latest_listings_count : 12;

if($cfg_show_latest_listings) {
	$query = "SELECT
		p.place_id, p.place_name, p.city_id, p.description, p.address, p.feat,
		ph.dir, ph.filename,
		c.city_name, c.slug,
		r.user_id, r.rating, r.text,
		u.first_name, u.last_name
		FROM places p
		LEFT JOIN photos ph ON p.place_id = ph.place_id
		LEFT JOIN cities c ON c.city_id = p.city_id
		LEFT JOIN reviews r ON p.place_id = r.place_id AND r.status = 'approved'
		LEFT JOIN users u ON r.user_id = u.id
		WHERE p.status = 'approved'
		GROUP BY p.place_id
		ORDER BY place_id DESC LIMIT :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':limit', $cfg_latest_listings_count);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		// reset $latest_listings array vars
		$latest_place_id             = '';
		$latest_place_name           = '';
		$latest_place_desc           = '';
		$latest_place_slug           = '';
		$latest_photo_url            = '';
		$latest_city_slug            = '';
		$review_user_profile_pic_url = '';
		$latest_username             = '';
		$latest_rating               = '';
		$latest_review_text          = '';

		// assign vars from query result
		$latest_place_id          = !empty($row['place_id'   ]) ? $row['place_id'   ] : '';
		$latest_place_name        = !empty($row['place_name' ]) ? $row['place_name' ] : '';
		$latest_place_desc        = !empty($row['description']) ? $row['description'] : '';
		$latest_city_name         = !empty($row['city_name'  ]) ? $row['city_name'  ] : '';
		$latest_city_slug         = !empty($row['slug'       ]) ? $row['slug'       ] : '';
		$latest_photo_dir         = !empty($row['dir'        ]) ? $row['dir'        ] : '';
		$latest_photo_filename    = !empty($row['filename'   ]) ? $row['filename'   ] : '';
		$latest_review_userid     = !empty($row['user_id'    ]) ? $row['user_id'    ] : '';
		$latest_review_first_name = !empty($row['first_name' ]) ? $row['first_name' ] : '';
		$latest_review_last_name  = !empty($row['last_name'  ]) ? $row['last_name'  ] : '';
		$latest_rating            = !empty($row['rating'     ]) ? $row['rating'     ] : '';
		$latest_review_text       = !empty($row['text'       ]) ? $row['text'       ] : '';

		// sanitize
		$latest_place_name        = e($latest_place_name);
		$latest_place_desc        = e($latest_place_desc);
		$latest_review_first_name = e($latest_review_first_name);
		$latest_review_last_name  = e($latest_review_last_name);
		$latest_review_text       = e($latest_review_text);

		// place name
		$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
		$latest_place_name = str_replace($endash, "-", $latest_place_name);

		// place slug
		$latest_place_slug = to_slug($latest_place_name);

		// limit description text length
		$latest_place_desc = implode(' ', array_slice(explode(' ', $latest_place_desc), 0, 10));

		// latest_photo_url
		$latest_photo_url = '';
		if(!empty($latest_photo_filename)) {
			$latest_photo_url = $pic_baseurl . '/' . $place_full_folder . '/' . $latest_photo_dir . '/' . $latest_photo_filename;
		}

		else {
			$latest_photo_url = $baseurl . '/imgs/empty.png';
		}

		// review with profile pics
		$latest_username = '';

		if(!empty($latest_review_userid)) {
			// user name
			$latest_review_last_name = mb_substr($latest_review_last_name, 0, 1);
			$latest_review_last_name = strtoupper($latest_review_last_name);
			$latest_username = "$latest_review_first_name $latest_review_last_name.";
			$latest_username = trim($latest_username);

			$folder = floor($latest_review_userid / 1000) + 1;
			if(strlen($folder) < 1) {
				$folder = '999';
			}

			$review_user_profile_pic_path = $pic_basepath . '/' . $profile_thumb_folder . '/' . $folder . '/' . $latest_review_userid;
			$review_user_profile_pic_path = glob("$review_user_profile_pic_path.*");

			if(empty($latest_username)) {
				$review_user_display_name = '';
			}

			if(!empty($review_user_profile_pic_path)) {
				$review_user_profile_pic_path     = explode('/', $review_user_profile_pic_path[0]);
				$review_user_profile_pic_filename = end($review_user_profile_pic_path);
				$review_user_profile_pic_url      = "$pic_baseurl/$profile_thumb_folder/$folder/$review_user_profile_pic_filename";
			}
			else {
				$review_user_profile_pic_url = "$baseurl/imgs/blank.png";
			}

			// limit review text
			$latest_review_text = implode(' ', array_slice(explode(' ', $latest_review_text), 0, 10));
		} // end if(!empty($latest_review_userid))

		$latest_place_link = $baseurl . '/' . $latest_city_slug . '/place/' . $latest_place_id . '/' . $latest_place_slug;

		// populate array
		$cur_loop = array(
			'place_id'        => $latest_place_id,
			'place_name'      => $latest_place_name,
			'place_desc'      => $latest_place_desc,
			'place_slug'      => $latest_place_slug,
			'place_link'      => $latest_place_link,
			'photo_url'       => $latest_photo_url,
			'place_city_slug' => $latest_city_slug,
			'profile_pic'     => $review_user_profile_pic_url,
			'user_name'       => $latest_username,
			'rating'          => $latest_rating ,
			'tip_text'        => $latest_review_text
			);

		$latest_listings[] = $cur_loop;
	}
}

/*--------------------------------------------------
Featured cities
--------------------------------------------------*/

$featured_cities = array();

$query = "SELECT
	c.*
	FROM cities c
	RIGHT JOIN cities_feat f ON c.city_id = f.city_id
	ORDER BY c.city_name";
$stmt = $conn->prepare($query);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$cur_loop = array(
		'city_id'    => $row['city_id'],
		'city_name'  => $row['city_name'],
		'state_abbr' => $row['state'],
		'state_id'   => $row['state_id'],
		'city_slug'  => $row['slug']
	);

	$featured_cities[] = $cur_loop;
}

// canonical
$canonical = $baseurl;

// template file
if(is_file(__DIR__ . '/templates/my_tpl_index.php')) {
	require_once(__DIR__ . '/templates/my_tpl_index.php');
}

else {
	require_once(__DIR__ . '/templates/tpl_index.php');
}