<?php 
require_once(__DIR__ . '/inc/config.php');
include('header.php'); 

function get_query_string(){

    $arr = explode("?",$_SERVER['REQUEST_URI']);
    if (count($arr) == 2){
        return "";
    }else{
        return "?".end($arr)."<br>";
    }       
}

$arr = explode("=",$_SERVER['REQUEST_URI']);
$place_id = $arr[1];
$where = 'WHERE p.place_id = '.$place_id;
// the query
$query = "SELECT
p.place_id, p.place_name, p.description, p.submission_date, p.feat_home, p.status, p.paid, p.userid,
p.address, p.phone, p.postal_code,p.cover_image,p.opening_hour,p.closing_hour,
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
GROUP BY p.place_id";
$stmt = $conn->prepare($query);
$stmt->execute();


while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
$place_id        = $row['place_id'];
$place_name      = $row['place_name'];
$description      = $row['description'];
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
$opening_hour = $row['opening_hour'];
$closing_hour = $row['closing_hour'];

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
'description'     => $description,
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
'cover_image' => $cover_image,
'opening_hour' => $opening_hour,
'closing_hour' => $closing_hour
);

$query = "SELECT * FROM `photos` WHERE place_id = $place_id";
$stmt = $conn->prepare($query);
$stmt->execute();
$images = [];
while($image = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$temp['filename'] = $image['filename'];
	$temp['dir'] = $image['dir'];
	$images[] = $temp;
}
$cur_loop_arr['images'] = $images;
$places_arr[] = $cur_loop_arr;
}
?>

	<!--LISTING DETAILS-->
	<section class="pg-list-1">
		<div class="container">
			<div class="row">
				<div class="pg-list-1-left"> <a href="!#"><h3> <?php echo $places_arr[0]['place_name'];?></h3></a>
					<!-- <div class="list-rat-ch"> <span>5.0</span> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> </div> -->
					<h4>7-Eleven has expanded to over 450 stores all over Singapore</h4>
					<p><b>Address:</b> <?php echo $places_arr[0]['address'];?></p>
					<div class="list-number pag-p1-phone">
						<ul>
							<li><i class="fa fa-phone" aria-hidden="true"></i> <?php echo $places_arr[0]['phone'];?></li>
							<!-- <li><i class="fa fa-envelope" aria-hidden="true"></i> localdir@webdir.com</li>
							<li><i class="fa fa-user" aria-hidden="true"></i> johny depp</li> -->
						</ul>
					</div>
				</div>
				<div class="pg-list-1-right">
					<div class="list-enqu-btn pg-list-1-right-p1">
						<ul>
							<!-- <li><a href="#ld-rew"><i class="fa fa-star-o" aria-hidden="true"></i> Write Review</a> </li>
							<li><a href="#"><i class="fa fa-commenting-o" aria-hidden="true"></i> Send SMS</a> </li> -->
							<?php if($settings[0]['call_now']) {?>
							<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i> Call Now</a> </li>
							<?php } ?>
							<?php if($settings[0]['get_enquiry']) {?>
							<li><a href="#!" data-dismiss="modal" data-toggle="modal" data-target="#list-quo"> <i class="fa fa-envelope" aria-hidden="true"></i> Enquire Now</a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<section class="list-pg-bg">
		<div class="container">
			<div class="row">
				<div class="com-padd">
					<div class="row">
						<div class="list-pg-lt list-page-com-p col-sm-12">
							<!--LISTING DETAILS: LEFT PART 1-->
							<div class="pglist-p1 pglist-bg pglist-p-com" id="ld-abour">
								<div class="pglist-p-com-ti">
									<h3><span>About</span> <?php echo $places_arr[0]['place_name'];?></h3>
									<?php if($settings[0]['social_media_share']) {?>
									<span class="home-list-pop-rat">
											<i class="fa fa-share-alt" aria-hidden="true"></i>
									</span>
									<?php } ?>
								</div>
								<div class="list-pg-inn-sp">
									<!-- <div class="share-btn">
										<ul>
											<li><a href="#"><i class="fa fa-facebook fb1"></i> Share On Facebook</a> </li>
											<li><a href="#"><i class="fa fa-twitter tw1"></i> Share On Twitter</a> </li>
											<li><a href="#"><i class="fa fa-google-plus gp1"></i> Share On Google Plus</a> </li>
										</ul>
									</div> -->
									<?php echo $places_arr[0]['description'];?>
									
								</div>
							</div>
							<!--END LISTING DETAILS: LEFT PART 1-->

							<!--LISTING DETAILS: LEFT PART 3-->
							<div class="pglist-p3 pglist-bg pglist-p-com" id="ld-gal">
								<div class="pglist-p-com-ti">
									<h3><span>Photo</span> Gallery</h3>
								</div>
								<div class="list-pg-inn-sp">
									<!-- <div id="myCarousel" class="carousel slide" data-ride="carousel">
										<ol class="carousel-indicators">
											<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
											<li data-target="#myCarousel" data-slide-to="1"></li>
											<li data-target="#myCarousel" data-slide-to="2"></li>
											<li data-target="#myCarousel" data-slide-to="3"></li>
										</ol>
										<div class="carousel-inner">
											<div class="item active"> <img src="images/slider/1.jpg" alt="Los Angeles"> </div>
											<div class="item"> <img src="images/slider/2.jpg" alt="Chicago"> </div>
											<div class="item"> <img src="images/slider/3.jpg" alt="New York"> </div>
											<div class="item"> <img src="images/slider/4.jpg" alt="New York"> </div>
										</div>
										<a class="left carousel-control" href="#myCarousel" data-slide="prev"> <i class="fa fa-angle-left list-slider-nav" aria-hidden="true"></i> </a>
										<a class="right carousel-control" href="#myCarousel" data-slide="next"> <i class="fa fa-angle-right list-slider-nav list-slider-nav-rp" aria-hidden="true"></i> </a>
									</div> -->
									<div class="gallery">
									    <?php
										foreach($places_arr[0]['images'] as $image) {
										?>
										<img  alt="gallery" class="materialboxed" src="<?php echo $baseurl;?>/pictures/place-full/<?php echo $image['dir'];?>/<?php echo $image['filename'];?>">
										<?php 
										}
										?>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php include('footer.php'); ?>
