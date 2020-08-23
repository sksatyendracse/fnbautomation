<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-process-create-cat.php');

$params = $_POST;
$cat_name     = $params['cat_name'];
$plural_name  = $params['cat_name'];
$cat_order    = '';
$cat_parent   = $params['cat_parent'];

// trim
$cat_name     = trim($cat_name);
$plural_name  = trim($plural_name);
$cat_order    = trim($cat_order);
$cat_parent   = trim($cat_parent[0]);

// prepare vars
$cat_order  = (is_numeric($cat_order))  ? $cat_order  : 0;
$cat_parent = (is_numeric($cat_parent)) ? $cat_parent : 0;

// uploaded image
$uploaded_img = basename($_FILES['iconfont_tag']['name']);
// get file extension
$ext = pathinfo($uploaded_img, PATHINFO_EXTENSION);
$ext = mb_strtolower($ext);
// generate filename
$filename = date('y.m.d.H.i') . "-" . microtime(true) . "-" . mt_rand(0, 99999999) . '.' . $ext;
$place_pic_tmp = __DIR__  . '/images/cats/' . $filename;
$iconfont_tag = '';
if(@move_uploaded_file($_FILES['iconfont_tag']['tmp_name'], $place_pic_tmp)) {
	$iconfont_tag = $filename;
}

$backgroud_image = '';
// uploaded image
$uploaded_img = basename($_FILES['backgroud_image']['name']);
// get file extension
$ext = pathinfo($uploaded_img, PATHINFO_EXTENSION);
$ext = mb_strtolower($ext);
// generate filename
$filename = date('y.m.d.H.i') . "-" . microtime(true) . "-" . mt_rand(0, 99999999) . '.' . $ext;
$place_pic_tmp = __DIR__  . '/images/cats/' . $filename;
if(@move_uploaded_file($_FILES['backgroud_image']['tmp_name'], $place_pic_tmp)) {
	$backgroud_image = $filename;
}

if(!empty($cat_name)) {
	// insert into db
	$query = "INSERT INTO cats(name, plural_name, parent_id, iconfont_tag, backgroud_image, cat_order)
		VALUES(:name, :plural_name, :cat_parent, :iconfont_tag, :backgroud_image, :cat_order)";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':name'        , $cat_name);
	$stmt->bindValue(':plural_name' , $plural_name);
	$stmt->bindValue(':cat_parent'  , $cat_parent);
	$stmt->bindValue(':iconfont_tag', $iconfont_tag);
	$stmt->bindValue(':backgroud_image', $backgroud_image);
	$stmt->bindValue(':cat_order'   , $cat_order);
	if($stmt->execute()) {

		$category_id = $conn->lastInsertId();
		$rows = [];
		$blog_categories = $_POST['cat_parent'];
		foreach($blog_categories as $cat_id) {
			$temp['cat_id'] = $category_id;
			$temp['parent_id'] = $cat_id;
			$sql = "INSERT INTO cat_parent (cat_id, parent_id) VALUES (:cat_id, :parent_id)";
			$stmt= $conn->prepare($sql);
			try{
				$stmt->execute($temp);
			}catch(Exception $e) {
				echo 'Message: ' .$e->getMessage();
			  }			
		}
		echo $txt_cat_created;
	}
}
else {
	echo $txt_cat_name_empty;
}
//header("Location: admin-cats");
?>
<script>window.location="<?php echo $baseurl;?>/admin/admin-cats";</script>
