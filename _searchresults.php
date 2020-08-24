<?php
require_once(__DIR__ . '/inc/config.php');
include('header.php'); 
/*--------------------------------------------------
init
--------------------------------------------------*/
$total_rows = 0;
$response = array();
$query = "SELECT * FROM cities WHERE city_name LIKE '%".$_GET['city_id']."%'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);


$total_cat_rows = 0;
$response_cat = array();
$query_cat = "SELECT * FROM cats WHERE name LIKE '%".$_GET['query']."%'";
$stmt_cat = $conn->prepare($query_cat);
$stmt_cat->execute();
$row_cat = $stmt_cat->fetch(PDO::FETCH_ASSOC);

$cityNamaQ = isset($row['city_id'])?$row['city_id']:$_GET['city_id'];
$catNamaQ = isset($row_cat['id'])?$row_cat['id']:$_GET['query'];
$query_city_id = !empty($_GET['city_id']) ? $cityNamaQ : 0;
$keyword       = !empty($_GET['query'  ]) ? $catNamaQ : '';
$page          = !empty($_GET['page'   ]) ? $_GET['page'   ] : 1;

// fix v.1.13
$user_query = e($keyword);

// check if keyword is *
if($keyword == '*') {
	$keyword = '';
}


// check page
if(!is_numeric($page)) {
	header("HTTP/1.0 404 Not Found");
	die('Invalid page');
}

$page = (int)$page;

/*--------------------------------------------------
pagination
--------------------------------------------------*/
$limit = 5;

if($page > 1) {
	$offset = ($page-1) * $limit + 1;
}

else {
	$offset = 1;
}

// get page
if($page == 1) {
	$pag = '';
}

else {
	$pag = "- $txt_page $page";
}

if(intval($query_city_id) && intval($keyword)) {
	$query = "SELECT COUNT(*)  AS total_rows
	FROM places p
	LEFT JOIN cities c ON p.city_id = c.city_id
	LEFT JOIN rel_place_cat rel ON p.place_id = rel.place_id
	WHERE p.city_id = $query_city_id AND rel.cat_id = $keyword AND p.status = 'approved' AND paid = 1";
} else if(intval($query_city_id)) {
	$query = "SELECT COUNT(*)  AS total_rows
	FROM places p
	LEFT JOIN cities c ON p.city_id = c.city_id
	LEFT JOIN rel_place_cat rel ON p.place_id = rel.place_id
	WHERE p.city_id = $query_city_id AND p.status = 'approved' AND paid = 1";
} else if(intval($keyword)) {
	$query = "SELECT  COUNT(*)  AS total_rows
	FROM places p
	LEFT JOIN cities c ON p.city_id = c.city_id
	LEFT JOIN rel_place_cat rel ON p.place_id = rel.place_id
	WHERE rel.cat_id = $keyword AND p.status = 'approved' AND paid = 1";
} else {
	$query = "SELECT COUNT(*)  AS total_rows
	FROM places p
	LEFT JOIN cities c ON p.city_id = c.city_id
	WHERE (address like '%".$query_city_id."%' OR place_name like '%".$keyword."%') AND p.status = 'approved' AND paid = 1";

}
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_rows = $row['total_rows'];

$pager = new DirectoryApp\PageIterator($limit, $total_rows, $page);
$start = $pager->getStartRow();

if(intval($query_city_id) && intval($keyword)) {
	$query = "SELECT p.place_id, p.place_name, p.address, p.cross_street,
	p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.description, p.cover_image,
	c.city_name, c.slug, c.state
	FROM places p
	LEFT JOIN cities c ON p.city_id = c.city_id
	LEFT JOIN rel_place_cat rel ON p.place_id = rel.place_id
	WHERE p.city_id = :city_id AND rel.cat_id = :cat_id AND p.status = 'approved' AND paid = 1
	GROUP BY p.place_id
	ORDER BY p.feat DESC
	LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $query_city_id);
	$stmt->bindValue(':cat_id', $keyword);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();
} else if(intval($query_city_id)) {
	$query = "SELECT p.place_id, p.place_name, p.address, p.cross_street,
	p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.description, p.cover_image,
	c.city_name, c.slug, c.state
	FROM places p
	LEFT JOIN cities c ON p.city_id = c.city_id
	LEFT JOIN rel_place_cat rel ON p.place_id = rel.place_id
	WHERE p.city_id = :city_id AND p.status = 'approved' AND paid = 1
	GROUP BY p.place_id
	ORDER BY p.feat DESC
	LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $query_city_id);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();
} else if(intval($keyword)) {
	$query = "SELECT p.place_id, p.place_name, p.address, p.cross_street,
	p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.description, p.cover_image,
	c.city_name, c.slug, c.state
	FROM places p
	LEFT JOIN cities c ON p.city_id = c.city_id
	LEFT JOIN rel_place_cat rel ON p.place_id = rel.place_id
	WHERE rel.cat_id = :cat_id AND p.status = 'approved' AND paid = 1
	GROUP BY p.place_id
	ORDER BY p.feat DESC
	LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':cat_id', $keyword);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();
} else {
	$query = "SELECT p.place_id, p.place_name, p.address, p.cross_street,
	p.postal_code, p.phone, p.area_code, p.lat, p.lng, p.state_id, p.description, p.cover_image,
	c.city_name, c.slug, c.state
	FROM places p
	LEFT JOIN cities c ON p.city_id = c.city_id
	WHERE (address like '%".$query_city_id."%' OR place_name like '%".$keyword."%') AND p.status = 'approved' AND paid = 1
	GROUP BY p.place_id
	ORDER BY p.feat DESC
	LIMIT :start, :limit";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':start', $start);
	$stmt->bindValue(':limit', $limit);
	$stmt->execute();
}
// now execute query

$places_arr = [];

/*--------------------------------------------------
Create list_items array
--------------------------------------------------*/
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$total_rows++;
		$place_id         = $row['place_id'];
		$place_name       = $row['place_name'];
		$address          = $row['address'];
		$cross_street     = $row['cross_street'];
		$place_city_name  = $row['city_name'];
		$place_city_slug  = $row['slug'];
		$place_state_id   = $row['state_id'];
		$place_state_abbr = $row['state'];
		$postal_code      = $row['postal_code'];
		$area_code        = $row['area_code'];
		$phone            = $row['phone'];
		$lat              = $row['lat'];
		$lng              = $row['lng'];
		$description      = $row['description'];
		$cover_image      = $row['cover_image'];

		// short description
		$description = get_snippet($description, 20);

		// cat icon (just use blank img for now)
		$cat_icon = $baseurl . '/imgs/blank.png';

		// clean place name
		$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
		$place_name = str_replace($endash, "-", $place_name);

		$places_arr[] = array(
			'place_id'         => $place_id,
			'place_name'       => e($place_name),
			'place_slug'       => to_slug($place_name),
			'address'          => e($address),
			'cross_street'     => e($cross_street),
			'place_city_name'  => $place_city_name,
			'place_city_slug'  => $place_city_slug,
			'place_state_abbr' => $place_state_abbr,
			'postal_code'      => e($postal_code),
			'area_code'        => e($area_code),
			'phone'            => e($phone),
			'lat'              => $lat,
			'lng'              => $lng,
			'cat_icon'         => $cat_icon,
			'description'      => $description,
			'cover_image'      => $cover_image
		);
}
$stmt->closeCursor();

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
								<h4><?php echo $row['place_city_name'];?></h4>
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
							$actual_link = $baseurl.'/_searchresults.php?city_id='.$_GET['city_id'].'&query='.$_GET['query'];
							$page_url = $actual_link."&page=";
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
