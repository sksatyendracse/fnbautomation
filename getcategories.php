<?php
require_once(__DIR__ . '/inc/config.php');

// main categories
$query = "SELECT * FROM cats WHERE cat_status = 1 AND parent_id = 0 ORDER BY cat_order";
$stmt = $conn->prepare($query);
$stmt->execute();

$main_cats =  [];
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $name = $row['name'];
    $main_cats["$name"] = !empty($row['backgroud_image'])?$baseurl.'/admin/images/cats/'.$row['backgroud_image']:"";
}
echo json_encode($main_cats);
?>