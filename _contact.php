<?php
require_once(__DIR__ . '/inc/config.php');

// v.1.13
if(!isset($txt_subject)) $txt_subject = "Subject";

// template file
if(is_file(__DIR__ . '/templates/my_tpl_contact.php')) {
	require_once(__DIR__ . '/templates/my_tpl_contact.php');
}

else {
	require_once(__DIR__ . '/templates/tpl_contact.php');
}