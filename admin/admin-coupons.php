<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-coupons.php');

// path info
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

// paging vars
$page = !empty($frags[3]) ? $frags[3] : 1;
$limit = $items_per_page;
if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}
else {
	$offset = 1;
}

/*--------------------------------------------------
Delete coupon
--------------------------------------------------*/
if(isset($_GET['event']) && $_GET['event'] == 'delete') {
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
			echo $coupon_img;
		}

		// delete coupon
		$query = "DELETE FROM coupons WHERE id = :id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':id', $_GET['coupon']);
		$stmt->execute();

		header("Location: $baseurl/admin/admin-coupons.php");
	}
}

/*--------------------------------------------------
Show Coupons
--------------------------------------------------*/
else {
	// get coupons for this user
	$query = "SELECT * FROM coupons";
	$stmt = $conn->prepare($query);
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
?>
<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang; ?>" > <![endif]-->
<html lang="<?= $html_lang; ?>" >
<head>
<title><?= $txt_html_title; ?> - <?= $site_name; ?></title>
<?php require_once(__DIR__ . '/_admin_html_head.php'); ?>
<style>

</style>
</head>
<body class="admin-cats">
<?php require_once(__DIR__ . '/_admin_header.php'); ?>
<div class="wrapper">
	<div class="menu-box">
		<?php require_once(__DIR__ . '/_admin_menu.php'); ?>
	</div>

	<div class="main-container">
		<h2><?= $txt_main_title; ?></h2>

		<div class="padding">
			<?php
			if(!empty($coupons_arr)) {
				foreach($coupons_arr as $k => $v) {
					?>
					<div class="block">
						<div class="user-item coupon" id="coupon-<?= $v['coupon_id'] ?>">
							<div class="user-item-pic coupon-img" id="<?= $v['coupon_id'] ?>">
								<a href=""><img src="<?= $v['coupon_img'] ?>" /></a>
							</div><!-- .user-item-pic -->

							<div class="user-item-description coupon-body">
								<div class="user-item-title block" id="coupon-<?= $v['coupon_id'] ?>">
									<h3><a href=""><?= e($v['coupon_title']) ?></a></h3>
								</div>

								<div class="block"><?= e($v['coupon_description']) ?></div>

								<!-- controls -->
								<div class="controls">
									<a href="?event=delete&coupon=<?= $v['coupon_id'] ?>" class="btn btn-default btn-less-padding"><i class="fa fa-trash" aria-hidden="true"></i> <?= $txt_coupon_del ?></a>
								</div>
							</div><!-- .user-item-description -->
							<div class="clear"></div>
						</div><!-- .user-item -->
					</div><!-- .block -->
					<?php
				} // end foreach
			} // end if($total_rows > 0)
			else {
				?>
				<div class="block"><?= $txt_no_coupons ?></div>
				<?php
			}
			?>
		</div><!-- .padding -->

	</div><!-- .main-container -->

	<div class="clear"></div>
</div><!-- .wrapper -->

<?php require_once(__DIR__ . '/_admin_footer.php'); ?>

<!-- javascript -->

</body>
</html>