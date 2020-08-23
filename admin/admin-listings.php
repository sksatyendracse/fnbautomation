<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-cats.php');

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

// get listings
// count how many
if(empty($_GET['s'])) {
	$query = "SELECT COUNT(*) AS total_rows FROM places WHERE status <> 'trashed'";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$total_rows = $row['total_rows'];

	if($sort == 'pending') {
		$query = "SELECT COUNT(*) AS total_rows FROM places WHERE status = 'pending'";
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
			GROUP BY p.place_id
			ORDER BY $orderby LIMIT :start, :limit";
		$stmt = $conn->prepare($query);
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

	$query = "SELECT COUNT(*) AS total_rows FROM places WHERE MATCH(place_name, description) AGAINST (:s) AND status <> 'trashed'";
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

<?php require_once(__DIR__ . '/_admin_header.php'); ?>


			<!--== BODY INNER CONTAINER ==-->
			<div class="sb2-2">
				<!--== breadcrumbs ==-->
				<div class="sb2-2-2">
					<ul>
						<li><a href="<?= $baseurl; ?>/admin/"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
						<li class="active-bre"><a href="<?= $baseurl; ?>/admin/admin-listings/"> All Listing</a> </li>
						<li class="page-back"><a href="<?= $baseurl; ?>/admin/"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>All Listing <a class="add-btn" href="<?= $baseurl ?>/user/add-place/2">Add New</a></h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-inn-page">
									<div class="tab-inn ad-tab-inn">
										<div class="table-responsive">
											<table class="table table-hover">
												<thead>
													<tr>
														<th>Listing</th>
														<th>Name</th>
														<th>Phone</th>
														<th>Country</th>
														<th>Listing Category</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												<?php     
												foreach($places_arr as $k => $row) {   
													// Display each field of the records.    
												?> 
                          <tr>
                            <td><span class="list-img"><img src="<?= $baseurl; ?>/admin/images/listing/<?php echo $row['cover_image'];?>" alt=""></span> </td>
														<td><a href="#"><span class="list-enq-name"><?php echo $row['place_name'];?></span><span class="list-enq-city"><?php echo $row['address'];?></span></a> </td>
														<td><?php echo $row['phone'];?>,<br> <?php echo $row['postal_code'];?></td>
														<td>Singapore</td>
														<td><?php echo $row['name'];?></td>
														<td> <span class="label label-success"><?php echo ucfirst($row['status']);?></span> </td>
														<td>
                              <div class="btn-set">
                                <a class="dropdown-button drop-down-meta" href="#" data-activates="dr-list1<?php echo $row['place_id'];?>"><i class="material-icons">more_vert</i></a>
            										<ul id="dr-list1<?php echo $row['place_id'];?>" class="dropdown-content">
            											<li><a href="<?= $baseurl ?>/user/edit-place/<?php echo $row['place_id'];?>">Edit</a> </li>
                                  <li><a href="list-view.php">View Details</a> </li>
            											<li><a href="#!" class="confirm">Delete</a> </li>
            										</ul>
                              </div>
                            </td>
													</tr>
												<?php } ?>
                          

												</tbody>
											</table>
										</div>
									</div>
								</div>
								
								<?php
				// pagination for regular listings
				if(empty($_GET['s'])) {
					?>
					<div class="admin-pag-na">
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
									$page_url = "$baseurl/admin/admin-listings/$sort/page/";
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
					<?php
				// pagination for search query
				} ?>
								
							</div>
						</div>
					</div>
				</div>
			</div>


<?php require_once(__DIR__ . '/_admin_footer.php'); ?>
