<?php 
require_once(__DIR__ . '/inc/config.php');
include('header.php'); 
$query = "SELECT * FROM pages WHERE page_id=1";
$stmt = $conn->prepare($query);
$stmt->execute();
$blog_array = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$blog_array[] = $row;
}
echo $blog_array[0]['page_contents'];
?>


<?php include('footer.php'); ?>
