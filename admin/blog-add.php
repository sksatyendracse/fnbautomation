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
						<li class="active-bre"><a href="#"> Add Blog</a> </li>
						<li class="page-back"><a href="<?= $baseurl; ?>/admin"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>Add New Blog</h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-mar-to-min">
									<div class="tab-inn ad-tab-inn">
										<div class="tz2-form-pay tz2-form-com ad-noto-text">
											<form method="post" action="<?= $baseurl; ?>/admin/admin-process-create-blog.php" enctype="multipart/form-data">
												<div class="row">
													<div class="input-field col s12">
														<input type="text" name="title" class="validate">
														<label>Title</label>
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<textarea id="summernote" class="materialize-textarea" name="description"></textarea>
														<!--label for="textarea1" class="">Descriptions</label-->
													</div>
												</div>
												<div class="row">
													<div class="input-field col s12">
														<select multiple="" name="blog_category[]">
															<option value="" disabled="" selected="">Select Category</option>
															<?php
																$query = "SELECT * FROM blog_category WHERE status = 1 ORDER BY name";
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
															
															?>
														</select>
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
												<div class="row tz-file-upload">
													<div class="file-field input-field">
														<div class="tz-up-btn"> <span>Blog Background Image</span>
															<input type="file" name="background_image"> </div>
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
<!-- include summernote css/js-->
<link href="<?= $baseurl; ?>/lib/summernote/dist/summernote.css" rel="stylesheet">
<script src="<?= $baseurl; ?>/lib/summernote/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
	$('#summernote').summernote({
		placeholder: "Descriptions",
		height: 450,
		styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
		toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'picture', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			  ]
	});

	// edit page form submitted
    $('#submit').click(function(e){
		e.preventDefault();
		var post_url = '<?= $baseurl; ?>' + '/admin/admin-process-edit-page.php';

		$.post(post_url, {
			params: $('form.form-edit-page').serialize(),
			},
			function(data) {
				$('.edit-page').empty().html(data);

			}
		);
    });
});
</script>
