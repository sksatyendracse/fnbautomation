<?php
require_once(__DIR__ . '/../inc/config.php');
//require_once(__DIR__ . '/user_area_inc.php'); // for this page, don't include the userid check, instead, redirect to login

// template file
if(is_file(__DIR__ . '/templates/user_templates/my_tpl_thanks.php')) {
	require_once(__DIR__ . '/templates/user_templates/my_tpl_thanks.php');
}

else {
	require_once(__DIR__ . '/../templates/user_templates/tpl_thanks.php');
}