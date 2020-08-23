<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id
require_once($lang_folder . '/admin_translations/trans-index.php');

$query = "SELECT * FROM settings WHERE id=1";
$stmt = $conn->prepare($query);
$stmt->execute();
$settings = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$settings[] = $row;
}
?>
<?php require_once(__DIR__ . '/_admin_header.php'); ?>
			<!--== BODY INNER CONTAINER ==-->
			<div class="sb2-2">
				<!--== breadcrumbs ==-->
				<div class="sb2-2-2">
					<ul>
						<li><a href="<?= $baseurl; ?>/admin"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
						<li class="active-bre"><a href="#"> Admin Setting</a> </li>
						<li class="page-back"><a href="<?= $baseurl; ?>/admin"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>Admin Setting</h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-inn-page">
									<div class="tab-inn ad-tab-inn">
									<form method="post" id="form-settings" action="<?= $baseurl; ?>/admin/admin-process-edit-settings.php">
										<table class="responsive-table bordered">
											<tbody>
												<tr>
													<td>Profile Status</td>
													<td>:</td>
													<td>
														<div class="switch">
															<label> Deactivate
																<input type="checkbox" name="profile_status" <?php echo $settings[0]['profile_status']?'checked':''?>> <span class="lever"></span> Activate </label>
														</div>
													</td>
												</tr>
												<tr>
													<td>Call Now</td>
													<td>:</td>
													<td>
														<div class="switch">
															<label> Deactivate
																<input type="checkbox" name="call_now" <?php echo $settings[0]['call_now']?'checked':''?>> <span class="lever"></span> Activate </label>
														</div>
													</td>
												</tr>
												<tr>
													<td>Get Enquiry</td>
													<td>:</td>
													<td>
														<div class="switch">
															<label> Deactivate
																<input type="checkbox" name="get_enquiry" <?php echo $settings[0]['get_enquiry']?'checked':''?>> <span class="lever"></span> Activate </label>
														</div>
													</td>
												</tr>
												<tr>
													<td>Show Contact Info</td>
													<td>:</td>
													<td>
														<div class="switch">
															<label> No
																<input type="checkbox" name="show_contact_info" <?php echo $settings[0]['show_contact_info']?'checked':''?>> <span class="lever"></span> Yes </label>
														</div>
													</td>
												</tr>
												<tr>
													<td>Show Profile Info</td>
													<td>:</td>
													<td>
														<div class="switch">
															<label> No
																<input type="checkbox" name="show_profile_info" <?php echo $settings[0]['show_profile_info']?'checked':''?>> <span class="lever"></span> Yes </label>
														</div>
													</td>
												</tr>
												<tr>
													<td>Social Media Share</td>
													<td>:</td>
													<td>
														<div class="switch">
															<label> No
																<input type="checkbox" name="social_media_share" <?php echo $settings[0]['social_media_share']?'checked':''?>> <span class="lever"></span> Yes </label>
														</div>
													</td>
												</tr>
												<tr>
													<td>All Notifications</td>
													<td>:</td>
													<td>
														<div class="switch">
															<label> Deactivate
																<input type="checkbox" name="all_notifications" <?php echo $settings[0]['all_notifications']?'checked':''?>> <span class="lever"></span> Activate </label>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
										<div class="db-mak-pay-bot db-mak-sett-save">
											<a href="javascript:void(0);" onclick="document.getElementById('form-settings').submit();" class="waves-effect waves-light btn-large">Save Settings</a>
										</div>
									</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php require_once(__DIR__ . '/_admin_footer.php'); ?>
