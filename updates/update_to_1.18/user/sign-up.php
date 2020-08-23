<?php
require_once(__DIR__ . '/../inc/config.php');

// template file
if(is_file(__DIR__ . '/templates/user_templates/my_tpl_sign-up.php')) {
	require_once(__DIR__ . '/templates/user_templates/my_tpl_sign-up.php');
}

else {
	require_once(__DIR__ . '/../templates/user_templates/tpl_sign-up.php');
}