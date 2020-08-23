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

$query = "SELECT * FROM blogs WHERE id=".$arr[1];
$stmt = $conn->prepare($query);
$stmt->execute();
$blog_array = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$blog_array[] = $row;
}
?>

	<section class="inn-page-bg" style="background: url(<?php echo !empty($blog_array[0]['background_image'])?$baseurl.'/admin/images/blog/'.$blog_array[0]['background_image']:$baseurl.'/images/inn-bg.jpg'?>);">
		<div class="container">
			<div class="row">
				<div class="inn-pag-ban">
					<h2><?php echo $blog_array[0]['title']?></h2>
				</div>
			</div>
		</div>
	</section>
	<section class="p-about com-padd">
		<div class="container">
			<div class="row blog-single con-com-mar-bot-o">
				<div class="col-md-12">
					<div class="blog-title">
						<h3><?php echo $blog_array[0]['title']?></h3> <span><?php echo date('F d, Y', strtotime($blog_array[0]['created_at']));?></span>
					</div>
					<div class="blog-img">
						<img src="<?= $baseurl; ?>/admin/images/blog/<?php echo $blog_array[0]['image_path'];?>" alt="">
					</div>
					<div class="page-blog">
						<?php echo $blog_array[0]['description']?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php include('footer.php'); ?>
