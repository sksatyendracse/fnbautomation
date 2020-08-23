<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-process-edit-cat.php');


$params = $_POST;

$id       = $params['id'];
$name     = $params['name'];
$status  = $params['status'];
// trim
$name     = trim($name);

$image_path = '';
// uploaded image
$uploaded_img = basename($_FILES['image_path']['name']);
// get file extension
$ext = pathinfo($uploaded_img, PATHINFO_EXTENSION);
$ext = mb_strtolower($ext);
// generate filename
$filename = date('y.m.d.H.i') . "-" . microtime(true) . "-" . mt_rand(0, 99999999) . '.' . $ext;
$place_pic_tmp = __DIR__  . '/images/blog/' . $filename;
if(@move_uploaded_file($_FILES['image_path']['tmp_name'], $place_pic_tmp)) {
	$image_path = $filename;
}

if(!empty($name)) {
	// insert into db
	$query = "UPDATE blog_category SET
		name    = :name,
		status  = :status
		WHERE id = :id";
	if(!empty($image_path)) {
		$query = "UPDATE blog_category SET
		name    = :name,
		image_path = :image_path,
		status  = :status
		WHERE id = :id";
	} 
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':id'       , $id);
	$stmt->bindValue(':name'         , $name);
	if(!empty($image_path)) {
		$stmt->bindValue(':image_path' , $image_path);
	}
	$stmt->bindValue(':status'         , $status);


	if($stmt->execute()) {
		echo $txt_cat_edited;
	}
}
else {
	echo $txt_cat_name_empty;
}

//header("Location: blog-category");
?>
<script>window.location="<?php echo $baseurl;?>/admin/blog-category";</script>