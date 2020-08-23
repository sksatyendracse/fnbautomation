<?php
require_once(__DIR__ . '/inc/config.php');

/*--------------------------------------------------
set vars from path info
--------------------------------------------------*/
$frags = '';
$page = 1;
$pager_template = '';
$is_single = false;
$canonical = $baseurl . '/coupons';
$total_rows = 0;
$start = 0;

// debug
//$items_per_page = 3;

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

if(!empty($frags[1])) {
	if(ctype_digit($frags[1])) {
		$is_single = true;
		$canonical = $baseurl . '/coupons/' . $frags[1];
	}

	else if($frags[1] == 'page' && isset($frags[2]) && ctype_digit($frags[2])) {
		$page = $frags[2];
		$canonical = $baseurl . '/coupons/page/' . $frags[2];
	}

	else {
		$canonical = $baseurl . '/coupons';
	}
}

/*--------------------------------------------------
Get list of coupons for this page
--------------------------------------------------*/
if(!$is_single) {
	// init
	$coupons_arr = array();
	$coupon_create = false;

	// count total coupons
	$query = "SELECT COUNT(*) AS total_rows FROM coupons";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$total_rows = $row['total_rows'];

	if($total_rows > 0) {
		$pager = new DirectoryApp\PageIterator($items_per_page, $total_rows, $page);
		$start = $pager->getStartRow();

		$query = "SELECT * FROM coupons WHERE CURDATE() < expire LIMIT :start, :items_per_page";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':start', $start);
		$stmt->bindValue(':items_per_page', $items_per_page);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$coupon_id          = (!empty($row['id'         ])) ? $row['id'         ] : '';
			$coupon_title       = (!empty($row['title'      ])) ? $row['title'      ] : '';
			$coupon_description = (!empty($row['description'])) ? $row['description'] : '';
			$coupon_place_id    = (!empty($row['place_id'   ])) ? $row['place_id'   ] : '';
			$coupon_expire      = (!empty($row['expire'     ])) ? $row['expire'     ] : '2100-01-01 00:00:00';
			$coupon_img         = (!empty($row['img'        ])) ? $row['img'        ] : '';

			// format expire data
			$coupon_expire = new DateTime($coupon_expire);
			$coupon_expire = $coupon_expire->format("Y-m-d");

			// social media links
			$twitter_link = 'https://twitter.com/intent/tweet';
			$twitter_link.= '?text=' . rawurlencode($coupon_title);
			$twitter_link.= '&url=' . rawurlencode("$baseurl/coupons/$coupon_id");

			$mail_body =  rawurlencode($coupon_description) . '%0D%0A' . rawurlencode("$baseurl/coupons/$coupon_id");
			$mailto_link = 'mailto:?subject=' . rawurlencode($coupon_title) . '&body=' . $mail_body;

			$facebook_link = 'https://www.facebook.com/sharer/sharer.php';
			$facebook_link.= '?u=' . rawurlencode("$baseurl/coupons/$coupon_id");

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
							'coupon_img'         => $coupon_img_url,
							'twitter_link'       => $twitter_link,
							'facebook_link'      => $facebook_link,
							'mailto_link'        => $mailto_link,
							);

			// add cur loop to places array
			$coupons_arr[] = $cur_loop_arr;
		}
	}
}

/*--------------------------------------------------
Show single coupon
--------------------------------------------------*/
else {
	if(ctype_digit($frags[1])) {
		$coupon_id = $frags[1];

		$query = "SELECT *, IF(CURDATE() < expire, 'valid', 'expired') AS valid FROM coupons WHERE id = :coupon_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':coupon_id', $coupon_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$coupon_title       = (!empty($row['title'      ])) ? $row['title'      ] : '';
		$coupon_description = (!empty($row['description'])) ? $row['description'] : '';
		$coupon_place_id    = (!empty($row['place_id'   ])) ? $row['place_id'   ] : '';
		$coupon_expire      = (!empty($row['expire'     ])) ? $row['expire'     ] : '';
		$coupon_img         = (!empty($row['img'        ])) ? $row['img'        ] : '';
		$coupon_valid       = (!empty($row['valid'      ])) ? $row['valid'      ] : 'expired';

		// format expire data
		$coupon_expire = new DateTime($coupon_expire);
		$coupon_expire = $coupon_expire->format("Y-m-d");

		// coupon image
		$coupon_folder = substr($coupon_img, 0, 2);
		$coupon_img_url = $pic_baseurl . '/coupons/' . $coupon_folder . '/' . $coupon_img;

		// get city details to build place link
		$query = "SELECT c.slug, p.place_name FROM places p LEFT JOIN cities c ON p.city_id = c.city_id WHERE p.place_id = :place_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':place_id', $coupon_place_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$place_city_slug = (!empty($row['slug'])) ? $row['slug'] : 'location';
		$place_name = (!empty($row['place_name'])) ? $row['place_name'] : 'place';

		$place_link = $baseurl . '/' . $place_city_slug . '/place/' . $coupon_place_id . '/' . to_slug($place_name);
	}
}

/*--------------------------------------------------
page navigation
--------------------------------------------------*/

if($total_rows > 0) {
	$pager = new DirectoryApp\PageIterator($items_per_page, $total_rows, $page);
}

if(!empty($pager) && $pager->getTotalPages() > 1) {
	$curPage = $page;

	$startPage = ($curPage < 5) ? 1 : $curPage - 4;
	$endPage = 8 + $startPage;
	$endPage = ($pager->getTotalPages() < $endPage) ? $pager->getTotalPages() : $endPage;
	$diff = $startPage - $endPage + 8;
	$startPage -= ($startPage - $diff > 0) ? $diff : 0;

	$startPage = ($startPage == 1) ? 2 : $startPage;
	$endPage = ($endPage == $pager->getTotalPages()) ? $endPage - 1 : $endPage;

	if($total_rows > 0) {
		$page_url = "$baseurl/coupons/page/";

		if ($curPage > 1) {
			$pager_template .= "<li><a href=\"$page_url" . "1\">$txt_pager_page1</a></li>";
		}
		if ($curPage > 6) {
			$pager_template .= "<li><span>...</span></li>";
		}
		if ($curPage == 1) {
			$pager_template .= "<li class=\"active\"><span>$txt_pager_page1</span></li>";
		}
		for($i = $startPage; $i <= $endPage; $i++) {
			if($i == $page) {
				$pager_template .= "<li class=\"active\"><span>$i</span></li>";
			}
			else {
				$pager_template .= "<li><a href=\"$page_url" . "$i\">$i</a></li>";
			}
		}

		if($curPage + 5 < $pager->getTotalPages()) {
			$pager_template .= "<li><span>...</span></li>";
		}
		if($pager->getTotalPages() > 5) {
			$last_page_txt = $txt_pager_lastpage;
		}

		$last_page_txt = ($pager->getTotalPages() > 5) ? $txt_pager_lastpage : $pager->getTotalPages();

		if($curPage == $pager->getTotalPages()) {
			$pager_template .= "<li class=\"active\"><span>$last_page_txt</span></li>";
		}
		else {
			$pager_template .= "<li><a href=\"$page_url" . $pager->getTotalPages() . "\">$last_page_txt</a></li>";
		}
	} //  end if($total_rows > 0)
} //  end if(!empty($pager) && $pager->getTotalPages() > 1)

if(!empty($pager) && $pager->getTotalPages() == 1) {
	// do something
}

if($page == 1) {
	$pag = '';
}

else {
	$pag = "- $txt_page $page";
}

/*--------------------------------------------------
fix canonical and inexisting pages
--------------------------------------------------*/
if(isset($pager) && $page > $pager->getTotalPages()) {
	header("Location: $baseurl/coupons");
}

/*--------------------------------------------------
include template file
--------------------------------------------------*/
if(is_file(__DIR__ . '/templates/my_tpl_coupons.php')) {
	require_once(__DIR__ . '/templates/my_tpl_coupons.php');
}

else {
	require_once(__DIR__ . '/templates/tpl_coupons.php');
}