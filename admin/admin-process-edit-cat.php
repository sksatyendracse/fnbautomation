<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-process-edit-cat.php');


$params = $_POST;

$cat_id       = $params['cat_id'];
$cat_name     = $params['cat_name'];
$plural_name  = $params['cat_name'];
$cat_parent   = $params['cat_parent'];
$cat_order    = $params['cat_order'];;
$iconfont_tag = '';
// trim
$cat_name     = trim($cat_name);
$plural_name  = trim($plural_name);
if(count($cat_parent)) {
	$cat_parent   = trim(trim($cat_parent[0]));
} else {
	$cat_parent   = 0;
}

$iconfont_tag = trim($iconfont_tag);
$cat_order    = trim($cat_order);

// prepare vars
$cat_order    = (is_numeric($cat_order))  ? $cat_order  : 0;
$cat_parent   = (is_numeric($cat_parent)) ? $cat_parent : 0;
$iconfont_tag = htmlspecialchars_decode ($iconfont_tag);

// uploaded image
$uploaded_img = basename($_FILES['iconfont_tag']['name']);
// get file extension
$ext = pathinfo($uploaded_img, PATHINFO_EXTENSION);
$ext = mb_strtolower($ext);
// generate filename
$filename = date('y.m.d.H.i') . "-" . microtime(true) . "-" . mt_rand(0, 99999999) . '.' . $ext;
$place_pic_tmp = __DIR__  . '/images/cats/' . $filename;
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
	$query = "UPDATE cats SET
		name         = :name,
		plural_name  = :plural_name,
		parent_id    = :parent_id,
		cat_order    = :cat_order
		WHERE id = :cat_id";
	if(!empty($iconfont_tag) && !empty($backgroud_image)) {
		$query = "UPDATE cats SET
		name         = :name,
		plural_name  = :plural_name,
		parent_id    = :parent_id,
		iconfont_tag = :iconfont_tag,
		backgroud_image = :backgroud_image,
		cat_order    = :cat_order
		WHERE id = :cat_id";
	} else if(!empty($iconfont_tag)) {
		$query = "UPDATE cats SET
		name         = :name,
		plural_name  = :plural_name,
		parent_id    = :parent_id,
		iconfont_tag = :iconfont_tag,
		cat_order    = :cat_order
		WHERE id = :cat_id";
	} else if(!empty($backgroud_image)) {
		$query = "UPDATE cats SET
		name         = :name,
		plural_name  = :plural_name,
		parent_id    = :parent_id,
		backgroud_image = :backgroud_image,
		cat_order    = :cat_order
		WHERE id = :cat_id";
	}

	$stmt = $conn->prepare($query);
	$stmt->bindValue(':cat_id'       , $cat_id);
	$stmt->bindValue(':name'         , $cat_name);
	$stmt->bindValue(':plural_name'  , $plural_name);
	$stmt->bindValue(':parent_id'    , $cat_parent);
	if(!empty($iconfont_tag) && !empty($backgroud_image)) {
		$stmt->bindValue(':iconfont_tag' , $iconfont_tag);
		$stmt->bindValue(':backgroud_image' , $backgroud_image);
	} else if(!empty($iconfont_tag)) {
		$stmt->bindValue(':iconfont_tag' , $iconfont_tag);
	} else if(!empty($backgroud_image)) {
		$stmt->bindValue(':backgroud_image' , $backgroud_image);
	}
	$stmt->bindValue(':cat_order'    , $cat_order);

	if($stmt->execute()) {
		$category_id = $cat_id;
		$query = "DELETE FROM cat_parent WHERE cat_id = :cat_id";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':cat_id', $cat_id);
		$stmt->execute();

		
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

		echo $txt_cat_edited;
	}
}
else {
	echo $txt_cat_name_empty;
}

//header("Location: admin-cats");
?>
<script>window.location="<?php echo $baseurl;?>/admin/admin-cats";</script>