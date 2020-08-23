<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id
require_once($lang_folder . '/admin_translations/trans-index.php');
?>
<?php require_once(__DIR__ . '/_admin_header.php'); ?>
			<div class="sb2-2">
				<!--== breadcrumbs ==-->
				<div class="sb2-2-2">
					<ul>
						<li><a href="<?= $baseurl; ?>/admin"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
						<li class="active-bre"><a href="<?= $baseurl; ?>/admin/list-category-add/"> Add Listing Category</a> </li>
						<li class="page-back"><a href="<?= $baseurl; ?>/admin/""><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>Add Listing Category</h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-mar-to-min">
									<div class="tab-inn ad-tab-inn">
										<div class="tz2-form-pay tz2-form-com">
											<form method="post" action="<?= $baseurl; ?>/admin/admin-process-create-cat.php" enctype="multipart/form-data">
												<input type="hidden" name="csrf_token" value="<?= session_id(); ?>">
												<input type="hidden" name="cat_order" value="0">
												<div class="row">
													<div class="input-field col s12">
														<input type="text" class="validate" name="cat_name">
														<label>Category Name</label>
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<select name="cat_status">
															<option value="" disabled="" selected="">Select Status</option>
															<option value="1">Active</option>
															<option value="0">Non-Active</option>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<select multiple name="cat_parent[]">
															<option value="" disabled="" selected="">Select Type</option>
															<?php
															// select only first 2 levels (parent = o or parent whose parent = 0)
															$modal_cats_arr = array();
															$level_0_ids = array();

															$query = "SELECT * FROM cats WHERE parent_id = 0 AND cat_status = 1 ORDER BY name";
															$stmt = $conn->prepare($query);
															$stmt->execute();

															while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
																$cur_loop_array = array( 'id' => $row['id'], 'name' => $row['name'] );
																$modal_cats_arr[] = $cur_loop_array;
																$level_0_ids[] = $row['id'];
															}

															$in = '';
															foreach($level_0_ids as $k => $v) {
																if($k != 0) {
																	$in .= ',';
																}
																$in .= "$v";
															}

															if(!empty($in)) {
																$query = "SELECT * FROM cats WHERE parent_id IN($in) AND cat_status = 1 ORDER BY name";
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
																	<option value="<?= $v['id']; ?>"><?= $v['name']; ?></option>
																	<?php
																}
															}
															?>
														</select>
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
