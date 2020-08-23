<?php
session_start();
$sessdir = dirname(dirname(__FILE__)).'/session_dir';
ini_set('session.save_path', $sessdir); 
// database credentials
$db_host      = 'localhost';
$db_name      = 'fnbautom_dt';
$db_user      = 'root';
$db_user_pass = '123456';

// base url, where the script will be installed
// *please do not include a trailing slash*
$baseurl = 'http://www.example.com';

// include global logic
require_once('common.inc.php');