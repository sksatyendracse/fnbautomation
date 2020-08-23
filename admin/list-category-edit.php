<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id
require_once($lang_folder . '/admin_translations/trans-index.php');
?>
<?php require_once(__DIR__ . '/_admin_header.php'); 


// path info
$frags = '';
if(!empty($_SERVER['PATH_INFO'])) {
	$frags = $_SERVER['PATH_INFO'];
} else {
	if(!empty($_SERVER['ORIG_PATH_INFO'])) {
		$frags = $_SERVER['ORIG_PATH_INFO'];
	}
}

// frags still empty
if(empty($frags)) {
	$frags = (!empty($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
}

// explode frags string
$frags = explode("/", $frags);

$cat_id = $frags[1];

$query = "SELECT * FROM cats WHERE id = :cat_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':cat_id', $cat_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$id           = (!empty($row['id']          )) ? $row['id']           : '';
$name         = (!empty($row['name']        )) ? $row['name']         : '';
$plural_name  = (!empty($row['plural_name'] )) ? $row['plural_name']  : '';
$parent_id    = (!empty($row['parent_id']   )) ? $row['parent_id']    : '';
$iconfont_tag = (!empty($row['iconfont_tag'])) ? $row['iconfont_tag'] : '';
$cat_order    = (!empty($row['cat_order']   )) ? $row['cat_order']    : '';
$cat_status    = (!empty($row['cat_status']   )) ? $row['cat_status']    : '';
$cat_parent    = (!empty($row['id']   )) ? $row['id']    : '';
// sanitize
$name          = e(trim($name        ));
$plural_name   = e(trim($plural_name ));
$iconfont_tag  = e(trim($iconfont_tag));

$query_cat = "SELECT parent_id FROM cat_parent WHERE cat_id = :cat_id";
$stmt_cat = $conn->prepare($query_cat);
$stmt_cat->bindValue(':cat_id', $cat_id);
$stmt_cat->execute();
$category_arr = array();
while($row = $stmt_cat->fetch(PDO::FETCH_ASSOC)) {
	$category_arr[] =   $row ['parent_id'];
}
?>
			<div class="sb2-2">
				<!--== breadcrumbs ==-->
				<div class="sb2-2-2">
					<ul>
						<li><a href="index-1.html"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
						<li class="active-bre"><a href="#"> Edit Listing Category</a> </li>
						<li class="page-back"><a href="#"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>Edit Listing Category</h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-mar-to-min">
									<div class="tab-inn ad-tab-inn">
										<div class="tz2-form-pay tz2-form-com">
											<form method="post" action="<?= $baseurl; ?>/admin/admin-process-edit-cat.php" enctype="multipart/form-data">
												<input type="hidden" name="csrf_token" value="<?= session_id(); ?>">
												<input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">
												<div class="row">
													<div class="input-field col s12">
														<input type="text" class="validate" name="cat_name" value="<?php echo $name;?>">
														<label>Category Name</label>
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<select name="cat_status">
															<option value="" disabled="" selected="">Select Status</option>
															<option value="1" <?php echo $cat_status?'selected':'' ?>>Active</option>
															<option value="0" <?php echo !$cat_status?'selected':'' ?>>Non-Active</option>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<select multiple name="cat_parent[]">
															<option value="" disabled="" selected="">Select Type</option>
															<?php
															
																$query = "SELECT * FROM cats WHERE cat_status = 1 ORDER BY name";
																$stmt = $conn->prepare($query);
																$stmt->execute();

																while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
																	$modal_cats_arr[] = array('id' => $row['id'], 'name' => $row['name']);
																}

																function cmp($a, $b) {
																	return strcasecmp ($a['name'], $b['name']);
																}
																usort($modal_cats_arr, 'cmp');

																foreach($modal_cats_arr as $k => $v) {
																	?>
																	<option <?php echo in_array($v['id'], $category_arr)?'selected':'' ;?> value="<?= $v['id']; ?>"><?= $v['name']; ?></option>
																	<?php
																}
															
															?>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<input type="number" class="validate" name="cat_order" value="<?php echo $cat_order;?>">
														<label>Category Order</label>
													</div>
												</div>
												<div class="row tz-file-upload">
													<div class="file-field input-field">
														<div class="tz-up-btn"> <span>Icon Image</span>
															<input type="file" name="iconfont_tag"> </div>
														<div class="file-path-wrapper">
															<input class="file-path validate" type="text"> </div>
													</div>
												</div>
												<div class="row tz-file-upload">
													<div class="file-field input-field">
														<div class="tz-up-btn"> <span>Background Image</span>
															<input type="file" name="backgroud_image"> </div>
														<div class="file-path-wrapper">
															<input class="file-path validate" type="text"> </div>
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<input type="submit" value="SUBMIT" class="waves-effect waves-light full-btn"> </div>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php require_once(__DIR__ . '/_admin_footer.php'); ?>
