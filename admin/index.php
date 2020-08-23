<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-cats.php');
?>
<script>
if(!localStorage.getItem("loggedin_token")) {
	window.location="<?php echo $baseurl;?>/user/login";
}
</script>
<?php
$sql = "SELECT count(*) FROM `blogs`"; 
$result = $conn->prepare($sql); 
$result->execute(); 
$blog_count = $result->fetchColumn(); 

$sql = "SELECT count(*) FROM `places`"; 
$result = $conn->prepare($sql); 
$result->execute(); 
$place_count = $result->fetchColumn(); 

$sql = "SELECT count(*) FROM `cats`"; 
$result = $conn->prepare($sql); 
$result->execute(); 
$list_cat_count = $result->fetchColumn(); 

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
			<li><a href="<?= $baseurl; ?>/admin"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
			<li class="active-bre"><a href="<?= $baseurl; ?>/admin"> Dashboard</a> </li>
			<!-- <li class="page-back"><a href="#"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li> -->
		</ul>
	</div>
	<div class="tz-2 tz-2-admin">
		<div class="tz-2-com tz-2-main">
			<h4>Dashboard</h4>
			<div class="tz-2-main-com bot-sp-20">
				<div class="tz-2-main-1 tz-2-main-admin">
					<div class="tz-2-main-2"> <img src="images/icon/d1.png" alt=""><span>All Listings</span>
						<h2><?php echo $place_count;?>
						</h2> </div>
				</div>
  <div class="tz-2-main-1 tz-2-main-admin">
					<div class="tz-2-main-2"> <img src="images/icon/1.png" alt=""><span>Listings Categories</span>
						<h2><?php echo $list_cat_count;?></h2> </div>
				</div>
				<div class="tz-2-main-1 tz-2-main-admin">
					<div class="tz-2-main-2"> <img src="images/icon/d4.png" alt=""><span>Blogs</span>
						<h2><?php echo $blog_count;?></h2> </div>
				</div>
			</div>

			<div class="split-row">
				<div class="col-md-12">
					<div class="box-inn-sp">
						<div class="inn-title">
							<h4>New Listing Details</h4>
							<p>Airtport Hotels The Right Way To Start A Short Break Holiday</p>
						</div>
						<div class="tab-inn">
							<div class="table-responsive table-desi">
								<table class="table table-hover">
									<thead>
										<tr>
				<th>Image</th>
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
            											<li><a href="#!">Delete</a> </li>
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
				</div>
			</div>

		</div>
	</div>
</div>
<?php require_once(__DIR__ . '/_admin_footer.php'); ?>
