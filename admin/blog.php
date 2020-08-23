<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-cats.php');

$connection       = new mysqli( $db_host, $db_user, $db_user_pass, $db_name);

$per_page_record = 4;  // Number of entries to show in a page.   
// Look for a GET variable page if not found default is 1.   

function get_query_string(){

    $arr = explode("?",$_SERVER['REQUEST_URI']);
    if (count($arr) == 2){
        return "";
    }else{
        return "?".end($arr)."<br>";
    }       
}

$arr = explode("=",$_SERVER['REQUEST_URI']);
if (isset($arr[1])) {    
	$page  = $arr[1];    
}    
else {    
	$page=1;    
}    
$start_from = ($page-1) * $per_page_record;     

$query = "SELECT * FROM blogs ORDER BY title LIMIT $start_from, $per_page_record";     
$rs_result = mysqli_query ($connection, $query);    
?>
<?php require_once(__DIR__ . '/_admin_header.php'); ?>

			<div class="sb2-2">
				<!--== breadcrumbs ==-->
				<div class="sb2-2-2">
					<ul>
						<li><a href="<?= $baseurl; ?>/admin"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
						<li class="active-bre"><a href="#">Blog</a> </li>
						<li class="page-back"><a href="<?= $baseurl; ?>/admin"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>Blog and Artical <a href="<?= $baseurl; ?>/admin/blog-add/" class="add-btn">Add New</a></h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-inn-page">
									<div class="tab-inn ad-tab-inn">
										<div class="table-responsive">
											<table class="table table-hover">
												<thead>
													<tr>
														<th>Image</th>
														<th>Background Image</th>
														<th>Blog Title</th>
														<th>Descriptions</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												<?php     
												while ($row = mysqli_fetch_array($rs_result)) {    
													// Display each field of the records.    
												?> 
												<tr>
														<td><span class="list-img"><img src="<?= $baseurl; ?>/admin/images/blog/<?php echo $row['image_path'];?>" alt=""></span> </td>
														<td><span class="list-img"><img src="<?= $baseurl; ?>/admin/images/blog/<?php echo $row['background_image'];?>" alt=""></span> </td>
														<td><a href="#"><span class="list-enq-name"><?php echo $row['title'];?></span></a> </td>
														<td><?php echo substr($row['description'], 0, 50);?></td>
														<td> <span class="label label-primary"><?php echo $row['status']?'Active':'In Active';?></span> </td>
														<td>
                              <div class="btn-set">
                                <a class="dropdown-button drop-down-meta" href="#" data-activates="dr-list3<?php echo $row['id'];?>"><i class="material-icons">more_vert</i></a>
            										<ul id="dr-list3<?php echo $row['id'];?>" class="dropdown-content">
            											<li><a href="<?= $baseurl; ?>/admin/blog-edit/<?php echo $row['id'];?>">Edit</a> </li>
            											<li><a href="<?= $baseurl; ?>/admin/blog-delete/<?php echo $row['id'];?>" data-blog-id="<?php echo $row['id'];?>" class="remove-blog">Delete</a> </li>
            										</ul>
                              </div>
                            </td>
													</tr>
												<?php } ?>
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<?php  
									$query = "SELECT COUNT(*) FROM  blogs";     
									$rs_result = mysqli_query($connection, $query);     
									$row = mysqli_fetch_row($rs_result);     
									$total_records = $row[0];     
									$total_pages = ceil($total_records / $per_page_record);     
									$pagLink = "";       
									?>
								<div class="admin-pag-na">
									<ul class="pagination list-pagenat">
										<?php if($page>=2){ ?>
										<li><a href="<?= $baseurl; ?>/admin/blog?page=<?= $page-1; ?>"><i class="material-icons">chevron_left</i></a> </li>
										<?php
										}		
										for ($i=1; $i<=$total_pages; $i++) {   
										if ($i == $page) {   
											$pagLink .= "<li class='active'><a class = 'active' href='".$baseurl."/admin/blog?page=".$i."'>".$i." </a></li>";   
										}               
										else  {   
											$pagLink .= "<li class='waves-effect'><a href='".$baseurl."/admin/blog?page=".$i."'>   
																				".$i." </a></li>";     
										}   
										};     
										echo $pagLink; 
										?>  
										<?php if($page<$total_pages){?>
										<li class="waves-effect"><a href="<?= $baseurl; ?>/admin/blog?page=<?= $page+1; ?>"><i class="material-icons">chevron_right</i></a> </li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php require_once(__DIR__ . '/_admin_footer.php'); ?>

<!-- javascript -->
<script src="<?= $baseurl; ?>/lib/jinplace/jinplace.min.js"></script>
<script>
$(document).ready(function(){
			// remove category
	$('.remove-blog').click(function(e) {
		e.preventDefault();
		var blog_id = $(this).data('blog-id');
		var post_url = '<?= $baseurl; ?>' + '/admin/admin-process-remove-blog.php';
		var wrapper = '#blog-' + blog_id;
		var csrf_token = "<?= session_id(); ?>";
		$.post(post_url, {
			blog_id: blog_id,
			csrf_token: csrf_token
			},
			function(data) {
				location.reload(true); 
				if(data) {
					$(wrapper).empty();
				}
			}
		);
	});
});
</script>
