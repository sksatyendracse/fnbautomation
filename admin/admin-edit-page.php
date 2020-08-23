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
}

else {
	if(!empty($_SERVER['ORIG_PATH_INFO'])) {
		$frags = $_SERVER['ORIG_PATH_INFO'];
	}
}
// frags still empty
if(empty($frags)) {
	$frags = (!empty($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
}
$frags = explode("/", $frags);

$page_id = $frags[1];

$query = "SELECT * FROM pages WHERE page_id = :page_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':page_id', $page_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$page_id       = $row['page_id'];
$page_title    = $row['page_title'];
$page_slug     = $row['page_slug'];
$meta_desc     = $row['meta_desc'];
$page_contents = $row['page_contents'];
$page_group    = $row['page_group'];
$page_order    = $row['page_order'];

// sanitize
$page_title    = e(trim($page_title));
$page_slug     = e(trim($page_slug));
$meta_desc     = e(trim($meta_desc));
$page_contents = e(trim($page_contents));
$page_group    = e(trim($page_group));
?>
<style>
.note-btn.btn {
	padding: 4px 8px;
}

.note-group-select-from-files {
	display: none;
}
</style>

<div class="sb2-2">
			<div class="sb2-2-2">
				<ul>
					<li><a href="<?= $baseurl; ?>/admin/"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
					<li class="active-bre"><a href="#"> About Us</a> </li>
					<li class="page-back"><a href="<?= $baseurl; ?>/admin/"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
				</ul>
			</div>
			<div class="tz-2 tz-2-admin">
				<div class="tz-2-com tz-2-main">
					<h4>About Us Page</h4>
					<!-- Dropdown Structure -->
					<div class="split-row">
						<div class="col-md-12">
							<div class="box-inn-sp ad-mar-to-min">
								<div class="tab-inn ad-tab-inn">
									<div class="tz2-form-pay tz2-form-com ad-noto-text">
										<form method="post" action="<?= $baseurl; ?>/admin/admin-process-edit-page.php">
											<input type="hidden" value="<?php echo $page_id;?>" name="page_id">
											<div class="row">
												<div class="input-field col s12">
													<input type="text" class="validate" name="page_title" value="<?php echo $page_title;?>">
													<label>Title</label>
												</div>
											</div>
											<div class="row">
												<div class="input-field col s12">
													<textarea id="summernote" class="materialize-textarea" name="page_contents"><?php echo $page_contents;?></textarea>
													<label for="textarea1" class="">Descriptions</label>
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
