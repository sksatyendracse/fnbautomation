<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id; die() if not admin
require_once($lang_folder . '/admin_translations/trans-process-remove-cat.php');

// csrf check
//require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$blog_id = $_POST['blog_id'];

$query = "DELETE FROM blogs WHERE id = $blog_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':id', $blog_id);
$stmt->execute();

$query = "DELETE FROM blog_category_id WHERE blog_id = $blog_id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':id', $blog_id);
$stmt->execute();

echo $txt_cat_removed;