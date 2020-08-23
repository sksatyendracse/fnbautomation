<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-process-edit-cat.php');


$params = $_POST;

$id       = $params['id'];
$title     = $params['title'];
$description     = $params['description'];
$status  = $params['status'];
// trim
$title     = trim($title);
$description     = trim($description);

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

$background_image = '';
// uploaded image
$uploaded_img = basename($_FILES['background_image']['name']);
// get file extension
$ext = pathinfo($uploaded_img, PATHINFO_EXTENSION);
$ext = mb_strtolower($ext);
// generate filename
$filename = date('y.m.d.H.i') . "-" . microtime(true) . "-" . mt_rand(0, 99999999) . '.' . $ext;
$place_pic_tmp = __DIR__  . '/images/blog/' . $filename;
if(@move_uploaded_file($_FILES['background_image']['tmp_name'], $place_pic_tmp)) {
	$background_image = $filename;
}

if(!empty($title)) {
	// insert into db
	$query = "UPDATE blogs SET
		title    = :title,
		description    = :description,
		status  = :status
		WHERE id = :id";
	if(!empty($image_path) && !empty($background_image)) {
		$query = "UPDATE blogs SET
		title    = :title,
		description    = :description,
		status  = :status,
		image_path = :image_path,
		background_image = :background_image
		WHERE id = :id";
	} else if(!empty($image_path)) {
		$query = "UPDATE blogs SET
		title    = :title,
		description    = :description,
		status  = :status,
		image_path = :image_path
		WHERE id = :id";
	} else if(!empty($background_image)) {
		$query = "UPDATE blogs SET
		title    = :title,
		description    = :description,
		status  = :status,
		background_image = :background_image
		WHERE id = :id";
	}
	
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':id'       , $id);
	$stmt->bindValue(':title'         , $title);
	$stmt->bindValue(':description'         , $description);
	$stmt->bindValue(':status'         , $status);

	if(!empty($image_path) && !empty($background_image)) {
		$stmt->bindValue(':image_path' , $image_path);
		$stmt->bindValue(':background_image' , $background_image);
	} else if(!empty($image_path)) {
		$stmt->bindValue(':image_path' , $image_path);
	} else if(!empty($background_image)) {
		$stmt->bindValue(':background_image' , $background_image);
	}

	if($stmt->execute()) {
		$blog_id = $id;
		$query = "DELETE FROM blog_category_id WHERE blog_id = :blog_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':blog_id', $blog_id);
		$stmt->execute();

		
		$rows = [];
		$blog_categories = $_POST['blog_category'];
		foreach($blog_categories as $cat_id) {
			$temp['blog_id'] = $blog_id;
			$temp['blog_category_id'] = $cat_id;
			$sql = "INSERT INTO blog_category_id (blog_id, blog_category_id) VALUES (:blog_id, :blog_category_id)";
			$stmt= $conn->prepare($sql);
			try{
				$stmt->execute($temp);
			}catch(Exception $e) {
				echo 'Message: ' .$e->getMessage();
			  }			
		}
		echo $txt_cat_edited;
	}
}
else {
	echo $txt_cat_name_empty;
}

//header("Location: blog-category");
?>
<script>window.location="<?php echo $baseurl;?>/admin/blog";</script>