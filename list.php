<?php 
require_once(__DIR__ . '/inc/config.php');
include('header.php'); 

// path info
$frags = '';
if(!empty($_SERVER['PATH_INFO'])) {
	$frags = $_SERVER['PATH_INFO'];
} else {
	if(!empty($_SERVER['ORIG_PATH_INFO'])) {
		$frags = $_SERVER['ORIG_PATH_INFO'];
	}
}

// frags still empty
if(empty($frags)) {
	$frags = (!empty($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
}

// explode frags string
$frags = explode("/", $frags);

$cateogry_id = intval($frags[1]);

$query = "SELECT * FROM cats WHERE id=".$cateogry_id;
$stmt = $conn->prepare($query);
$stmt->execute();
$category_array = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$category_array[] = $row;
}

// paging vars
$page = !empty($frags[3]) ? $frags[3] : 1;
$items_per_page = 6;
$limit = $items_per_page;
if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}
else {
	$offset = 1;
}

// sort order
$sort = !empty($frags[1]) ? $frags[1] : 'sort-date';

$places_arr = array();
// get listings
// count how many
if(empty($_GET['s'])) {
	$query = "SELECT COUNT(*) AS total_rows FROM places p LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id WHERE p.status <> 'trashed' AND rel.cat_id = $cateogry_id";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$total_rows = $row['total_rows'];

	if($sort == 'pending') {
		$query = "SELECT COUNT(*) AS total_rows FROM places p LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id WHERE p.status = 'pending' AND rel.cat_id = $cateogry_id";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];
	}

	if($total_rows > 0) {
		$pager = new DirectoryApp\PageIterator($limit, $total_rows, $page);
		$start = $pager->getStartRow();

		// sort, order by
		$orderby = "p.place_name";
		$where = "WHERE p.status <> 'trashed'";

		if($sort == 'sort-date') {
			$orderby = "p.place_id DESC";
		}

		if($sort == 'sort-name') {
			$orderby = "p.place_name";
		}

		if($sort == 'pending') {
			$where = "WHERE p.status = 'pending'";
		}

		if($sort == 'find') {
			$orderby = "p.place_name";
			$where = "WHERE p.place_id = " . (int)$frags[2];

			// first count
			$query = "SELECT COUNT(*) AS total_rows FROM places WHERE place_id = " . (int)$frags[2];
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$total_rows = $row['total_rows'];
		}

		// the query
		$query = "SELECT
				p.place_id, p.place_name, p.submission_date, p.feat_home, p.status, p.paid, p.userid,
				p.address, p.phone, p.postal_code,p.cover_image,
				c.city_name, c.slug, c.state,
				rel.cat_id,
				cats.name AS name,
				u.email,
				plans.plan_name
			FROM places p
				LEFT JOIN cities c ON p.city_id = c.city_id
				LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id
				LEFT JOIN cats ON rel.cat_id = cats.id
				LEFT JOIN users u ON u.id = p.userid
				LEFT JOIN plans ON plans.plan_id = p.plan
			$where
			AND rel.cat_id = $cateogry_id
			GROUP BY p.place_id
			ORDER BY $orderby LIMIT :start, :limit";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':start', $start);
		$stmt->bindValue(':limit', $limit);
		$stmt->execute();

		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$place_id        = $row['place_id'];
			$place_name      = $row['place_name'];
			$place_owner     = $row['userid'];
			$city_name       = $row['city_name'];
			$city_slug       = $row['slug'];
			$state_abbr      = $row['state'];
			$cat_name        = $row['name'];
			$submission_date = $row['submission_date'];
			$feat_home       = $row['feat_home'];
			$status          = $row['status'];
			$paid            = $row['paid'];
			$user_email      = $row['email'];
			$plan_name       = $row['plan_name'];
			$address       = $row['address'];
			$phone       = $row['phone'];
			$postal_code = $row['postal_code'];
			$cover_image = $row['cover_image'];

			// sanitize
			$place_name = e($place_name);
			$user_email = e($user_email);
			$place_slug = to_slug($place_name);

			// simplify date
			$submission_date = strtotime($submission_date);
			$date_formatted  = date( 'Y-m-d', $submission_date );

			// link to each place
			$link_url = $baseurl . '/' . $city_slug . '/place/' . $place_id . '/' . $place_slug;

			$cur_loop_arr = array(
				'place_id'       => $place_id,
				'place_name'     => $place_name,
				'place_owner'    => $place_owner,
				'place_slug'     => $place_slug,
				'link_url'       => $link_url,
				'city_name'      => $city_name,
				'city_slug'      => $city_slug,
				'state_abbr'     => $state_abbr,
				'name'       => $cat_name,
				'date_formatted' => $date_formatted,
				'feat_home'      => $feat_home,
				'status'         => $status,
				'paid'           => $paid,
				'user_email'     => $user_email,
				'plan_name'      => $plan_name,
				'address'      => $address,
				'phone'      => $phone,
				'postal_code' => $postal_code,
				'cover_image' => $cover_image
			);

			$places_arr[] = $cur_loop_arr;
		}
	}
}

else {
	$s    = $_GET['s'];
	$page = (!empty($_GET['page'])) ? $_GET['page'] : 1;

	$query = "SELECT COUNT(*) AS total_rows FROM places p LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id WHERE MATCH(p.place_name, description) AGAINST (:s) AND p.status <> 'trashed' AND rel.cat_id = $cateogry_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':s', $s);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$total_rows = $row['total_rows'];

	if($total_rows > 0) {
		$pager = new DirectoryApp\PageIterator($limit, $total_rows, $page);
		$start = $pager->getStartRow();

		// the query
		$query = "SELECT
				p.place_id, p.place_name, p.submission_date, p.feat_home, p.status, p.paid, p.userid,p.cover_image,
				c.city_name, c.slug, c.state,
				rel.cat_id,
				cats.name AS cat_name,
				u.email,
				plans.plan_name
			FROM places p
				LEFT JOIN cities c ON p.city_id = c.city_id
				LEFT JOIN rel_place_cat rel ON rel.place_id = p.place_id
				LEFT JOIN cats ON rel.cat_id = cats.id
				LEFT JOIN users u ON u.id = p.userid
				LEFT JOIN plans ON plans.plan_id = p.plan
			WHERE MATCH(place_name, description) AGAINST (:s) AND p.status <> 'trashed'
			AND rel.cat_id = $cateogry_id
			GROUP BY p.place_id
			ORDER BY p.place_id DESC
			LIMIT :start, :limit";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':s', $s);
		$stmt->bindValue(':start', $start);
		$stmt->bindValue(':limit', $limit);
		$stmt->execute();

		$places_arr = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$place_id        = $row['place_id'];
			$place_name      = $row['place_name'];
			$place_owner     = $row['userid'];
			$city_name       = $row['city_name'];
			$city_slug       = $row['slug'];
			$state_abbr      = $row['state'];
			$cat_name        = $row['cat_name'];
			$submission_date = $row['submission_date'];
			$feat_home       = $row['feat_home'];
			$status          = $row['status'];
			$paid            = $row['paid'];
			$user_email      = $row['email'];
			$plan_name       = $row['plan_name'];
			$cover_image       = $row['cover_image'];

			// sanitize
			$place_name = e($place_name);
			$user_email = e($user_email);
			$place_slug = to_slug($place_name);

			// simplify date
			$submission_date = strtotime($submission_date);
			$date_formatted  = date( 'Y-m-d', $submission_date );

			// link to each place
			$link_url = $baseurl . '/' . $city_slug . '/place/' . $place_id . '/' . $place_slug;

			$cur_loop_arr = array(
				'place_id'       => $place_id,
				'place_name'     => $place_name,
				'place_owner'    => $place_owner,
				'place_slug'     => $place_slug,
				'link_url'       => $link_url,
				'city_name'      => $city_name,
				'city_slug'      => $city_slug,
				'state_abbr'     => $state_abbr,
				'cat_name'       => $cat_name,
				'date_formatted' => $date_formatted,
				'feat_home'      => $feat_home,
				'status'         => $status,
				'paid'           => $paid,
				'user_email'     => $user_email,
				'plan_name'      => $plan_name,
				'cover_image'      => $cover_image
			);

			$places_arr[] = $cur_loop_arr;
		}
	}
}

// get array of ids for this render (not being used now, just for future updates)
if(!empty($places_arr)) {
	$places_ids = array();
	foreach($places_arr as $k => $v) {
		$places_ids[] = $v['place_id'];
	}

	// build $in var
	$in = '';
	$i = 0;
	foreach($places_arr as $k => $v) {
		if($i != 0) {
			$in .= ',';
			$in .= $v['place_id'];
		} else {
			$in .= $v['place_id'];
		}
		$i++;
	}

	// get custom slugs
	$slugs_arr = array();

	/* this is reserved for future versions
	$query = "SELECT place_id, slug FROM meta_data WHERE place_id IN ($in)";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':place_id', $place_id);
	$stmt->execute();



	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$place_id   = (!empty($row['place_id'])) ? $row['place_id'] : '';
		$place_slug = (!empty($row['slug']))     ? $row['slug']     : '';
		$slugs_arr[$place_id] = $place_slug;
	}
	*/
}

// translation var check if exists, if not, set default
// v. 1.09
$txt_transfer_owner = (!empty($txt_transfer_owner)) ? $txt_transfer_owner : "Transfer Listing to User ID: ";
?>


	<section class="dir-alp dir-pa-sp-top" style="background: url(<?php echo !empty($category_array[0]['backgroud_image'])?$baseurl.'/admin/images/cats/'.$category_array[0]['backgroud_image']:'../images/services/Main.jpg'?>);">
		<div class="container">
			<div class="row">
				<div class="dir-alp-tit">
					<h1>Digital Tool For Modern Facade Building dTMFb</h1>
				</div>
			</div>
			<div class="row mt-50">
				<div class="dir-alp-con">

					<div class="col-md-12 dir-alp-con-right">
						<div class="dir-alp-con-right-1">
							<div class="row">
							<?php     
							foreach($places_arr as $k => $row) {   
								// Display each field of the records.    
							?> 
								<!--LISTINGS-->
								<div class="home-list-pop list-spac">
									<!--LISTINGS IMAGE-->
									<div class="col-md-3 list-ser-img"> <img src="<?= $baseurl; ?>/admin/images/listing/<?php echo $row['cover_image'];?>" alt=""> </div>
									<!--LISTINGS: CONTENT-->
									<div class="col-md-9 home-list-pop-desc inn-list-pop-desc">
										<a href="<?= $baseurl; ?>/listing-details.php?id=<?php echo $row['place_id'];?>">
											<h3><?php echo $row['place_name'];?></h3>
										</a>
										<h4>Alexandra Hill, Singapore</h4>
										<p><b>Address:</b> <?php echo $row['address'];?></p>
										<div class="list-number">
											<ul>
												<li><img src="<?= $baseurl; ?>/admin/images/icon/phone.png" alt=""> <?php echo $row['phone'];?></li>
												<!-- <li><img src="images/icon/mail.png" alt=""> localdir@webdir.com</li> -->
											</ul>
										</div>
										<?php if($settings[0]['social_media_share']) {?>
										<span class="home-list-pop-rat">
											<i class="fa fa-share-alt" aria-hidden="true"></i>
										</span>
										<?php } ?>

										<div class="list-enqu-btn mt-20">
											<ul>
												<?php if($settings[0]['call_now']) {?>
												<li><a href="#!"><i class="fa fa-phone" aria-hidden="true"></i> Call Now</a> </li>
												<?php } ?>
												<?php if($settings[0]['get_enquiry']) {?>
												<li><a href="#!" data-dismiss="modal" data-toggle="modal" data-target="#list-quo"> <i class="fa fa-envelope" aria-hidden="true"></i> Enquire Now</a> </li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
								<?php
							} //  end foreach($response as $k => $v)
							if(!count($places_arr)) {
							?>
							<div class="home-list-pop list-spac" style="text-align:center;">
								<div class="container">
									<p style="text-align: center; font-weight:bold;">Coming Soon</p>
								</div>
							</div>
							<?php	
							}
							?>
								
							</div>
							<div class="row">
							<ul class="pagination list-pagenat">
							<?php
							if(isset($pager) && $pager->getTotalPages() > 1) {
								$curPage = $page;

								$startPage = ($curPage < 5)? 1 : $curPage - 4;
								$endPage = 8 + $startPage;
								$endPage = ($pager->getTotalPages() < $endPage) ? $pager->getTotalPages() : $endPage;
								$diff = $startPage - $endPage + 8;
								$startPage -= ($startPage - $diff > 0) ? $diff : 0;

								$startPage = ($startPage == 1) ? 2 : $startPage;
								$endPage = ($endPage == $pager->getTotalPages()) ? $endPage - 1 : $endPage;

								if($total_rows > 0) {
									$page_url = "$baseurl/list.php/$sort/page/";
									?>
									<?php
									if ($curPage > 1) {
										?>
										<li><a href="<?php echo $page_url, $curPage - 1; ?>"><i class="material-icons">chevron_left</i></a> </li>
										<li><a href="<?= $page_url; ?>1">1</a></li>
										<?php
									}
									if ($curPage > 6) {
										?>
										<li><a>...</a></li>
										<?php
									}
									if ($curPage == 1) {
										?>
										<li class="active"><a>1</a></li>
										<?php
									}
									for($i = $startPage; $i <= $endPage; $i++) {
										if($i == $page) {
											?>
											<li class="active"><a><?= $i; ?></a></li>
											<?php
										}
										else {
											?>
											<li><a href="<?php echo $page_url, $i; ?>"><?= $i; ?></a></li>
											<?php
										}
									}

									if($curPage + 5 < $pager->getTotalPages()) {
										?>
										<li><a>...</a></li>
										<?php
									}
									if($pager->getTotalPages() > 5) {
										$last_page_txt = $txt_pager_last_page;
									}

									$last_page_txt = ($pager->getTotalPages() > 5) ? $txt_pager_last_page : $pager->getTotalPages();

									if($curPage == $pager->getTotalPages()) {
										?>
										<li class="active"><a><?= $pager->getTotalPages(); ?></a></li>
										<?php
									}
									else {
										?>
										<li><a href="<?php echo $page_url, $pager->getTotalPages(); ?>"><?= $pager->getTotalPages(); ?></a></li>
										<li><a href="<?php echo $page_url, $curPage + 1; ?>"><i class="material-icons">chevron_right</i></a> </li>
										<?php
									}
									?>
									<?php
								} //  end if($total_rows > 0)
							} //  end if(isset($pager) && $pager->getTotalPages() > 1)
							if(isset($pager) && $pager->getTotalPages() == 1) {
								?>

								<?php
							}
							?>
						</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<!--MOBILE APP-->
<?php include('footer.php'); ?>
