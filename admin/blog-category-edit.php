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

$query = "SELECT * FROM blog_category WHERE id = :cat_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':cat_id', $cat_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$id           = (!empty($row['id']          )) ? $row['id']           : '';
$name         = (!empty($row['name']        )) ? $row['name']         : '';
$status    = (!empty($row['status']   )) ? $row['status']    : '';
// sanitize
$name          = e(trim($name));
$status   = e(trim($status ));
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
											<form method="post" action="<?= $baseurl; ?>/admin/admin-process-edit-blog-category.php" enctype="multipart/form-data">
												<input type="hidden" name="id" value="<?php echo $id;?>">
												<div class="row">
													<div class="input-field col s12">
														<input type="text" class="validate" name="name" value="<?php echo $name;?>">
														<label>Category Name</label>
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<select name="status">
															<option value="" disabled="" selected="">Select Status</option>
															<option value="1" <?php echo $status?'selected':'' ?>>Active</option>
															<option value="0" <?php echo !$status?'selected':'' ?>>Non-Active</option>
														</select>
													</div>
												</div>
												
												<div class="row tz-file-upload">
													<div class="file-field input-field">
														<div class="tz-up-btn"> <span>Category Image</span>
															<input type="file" name="image_path"> </div>
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
