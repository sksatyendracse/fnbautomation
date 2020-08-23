<?php
if($userid == 1) {
	$is_admin = 1;
}

// show currency cents
$cfg_has_cents = false;

// show latest listings on the home page
$cfg_show_latest_listings = true;

// number of latest listing to show on the home page
$cfg_latest_listings_count = 12;

// auto approve paid listings
$cfg_approve_after_paid = true;

// default approve profile picture
$cfg_default_approve_profile_pic = true;

// when not using city autocomplete, define drop down count limit
$cfg_city_dropdown_limit = 100;