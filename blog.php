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

$query = "SELECT blog_id FROM blog_category_id WHERE blog_category_id=".$arr[1];
$stmt = $conn->prepare($query);
$stmt->execute();
$blog_array = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$blog_array[] = intval($row['blog_id']);
}

$blogs = array();
if (count($blog_array )) {
	$array = implode(",",$blog_array);
	$query = "SELECT * FROM blogs WHERE id IN($array)";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$blogs[] = $row;
	}
}

$query = "SELECT * FROM blog_category WHERE id=".$arr[1];
$stmt = $conn->prepare($query);
$stmt->execute();
$blog_category = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$blog_category[] = $row;
}
?>

	<section class="inn-page-bg">
		<div class="container">
			<div class="row">
				<div class="inn-pag-ban">
					<h2><?php echo $blog_category[0]['name'];?></h2>
				</div>
			</div>
		</div>
	</section>
	<section class="p-about com-padd">
		<div class="container">
			<div class="row ">
				<?php 
				foreach($blogs as $key => $blog) {
				if($key > 2) {
					continue;
				}
				?>
				<div class="col-md-4">
					<div class="blog">
						<div class="blog-img"> <img src="<?= $baseurl; ?>/admin/images/blog/<?php echo $blog['image_path'];?>" alt=""> </div>
						<div class="page-blog">
							<h3><?php echo $blog['title'];?></h3> <span><?php echo date('F d, Y', strtotime($blog['created_at']));?></span>
							<p><?php echo substr($blog['description'], 0, 50);?>... </p> <a class="waves-effect waves-light btn-large full-btn" href="<?php echo $baseurl;?>/blog-content.php?id=<?php echo $blog['id'];?>">Read More</a> </div>
					</div>
				</div>
				<?php } 
				if(!count($blogs)) {?>
					<div class="col-md-12">
						<div class="blog" style="text-align: center;">
							<h3>Coming Soon</h3>
						</div>
					</div>
				<?php } ?>
				
			</div>
		</div>
	</section>


<?php include('footer.php'); ?>
