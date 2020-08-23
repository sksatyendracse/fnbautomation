<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-process-edit-cat.php');


$params = $_POST;
$id = 1;
$profile_status = isset($params['profile_status'])?1:0;
$call_now = isset($params['call_now'])?1:0;
$get_enquiry = isset($params['get_enquiry'])?1:0;
$show_contact_info  = isset($params['show_contact_info'])?1:0;
$show_profile_info  = isset($params['show_profile_info'])?1:0;
$social_media_share = isset($params['social_media_share'])?1:0;
$all_notifications  = isset($params['all_notifications'])?1:0;
$query = "UPDATE settings SET
		profile_status    = :profile_status,
		call_now    = :call_now,
		get_enquiry  = :get_enquiry,
		show_contact_info    = :show_contact_info,
		show_profile_info    = :show_profile_info,
		social_media_share  = :social_media_share,
		all_notifications    = :all_notifications
		WHERE id = :id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':id'       , $id);
	$stmt->bindValue(':profile_status'         , $profile_status);
	$stmt->bindValue(':call_now'         , $call_now);
	$stmt->bindValue(':get_enquiry'         , $get_enquiry);
	$stmt->bindValue(':show_contact_info'         , $show_contact_info);
	$stmt->bindValue(':show_profile_info'         , $show_profile_info);
	$stmt->bindValue(':social_media_share'         , $social_media_share);
	$stmt->bindValue(':all_notifications'         , $all_notifications);
	$stmt->execute();
	
//header("Location: blog-category");
?>
<script>window.location="<?php echo $baseurl;?>/admin/setting/";</script>