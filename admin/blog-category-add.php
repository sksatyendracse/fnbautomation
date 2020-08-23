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
						<li class="active-bre"><a href="#"> Add Blog Category</a> </li>
						<li class="page-back"><a href="<?= $baseurl; ?>/admin"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>Add Blog Category</h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-mar-to-min">
									<div class="tab-inn ad-tab-inn">
										<div class="tz2-form-pay tz2-form-com">
											<form method="post" action="<?= $baseurl; ?>/admin/admin-process-create-blog-category.php" enctype="multipart/form-data">
												<div class="row">
													<div class="input-field col s12">
														<input type="text" class="validate" name="name">
														<label>Blog Name</label>
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<select name="status">
															<option value="" disabled="" selected="">Select Status</option>
															<option value="1">Active</option>
															<option value="0">Non-Active</option>
														</select>
													</div>
												</div>
												
												<div class="row tz-file-upload">
													<div class="file-field input-field">
														<div class="tz-up-btn"> <span>Blog Image</span>
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
