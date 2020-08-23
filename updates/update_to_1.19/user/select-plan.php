<?php
require_once(__DIR__ . '/../inc/config.php');

if(empty($userid)) {
	$redir_url = $baseurl . '/user/login/select-plan';
	header("Location: $redir_url");
}

// get plans
$query = "SELECT * FROM plans WHERE plan_status = 1 ORDER BY plan_order";
$stmt = $conn->prepare($query);
$stmt->execute();

$plans_arr = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$plan_id           = $row['plan_id'];
	$plan_type         = !empty($row['plan_type'        ]) ? $row['plan_type'        ] : '';
	$plan_name         = !empty($row['plan_name'        ]) ? $row['plan_name'        ] : '';
	$plan_period       = !empty($row['plan_period'      ]) ? $row['plan_period'      ] : 0;
	$plan_description1 = !empty($row['plan_description1']) ? $row['plan_description1'] : '';
	$plan_description2 = !empty($row['plan_description2']) ? $row['plan_description2'] : '';
	$plan_description3 = !empty($row['plan_description3']) ? $row['plan_description3'] : '';
	$plan_description4 = !empty($row['plan_description4']) ? $row['plan_description4'] : '';
	$plan_description5 = !empty($row['plan_description5']) ? $row['plan_description5'] : '';
	$plan_price        = !empty($row['plan_price'       ]) ? $row['plan_price'       ] : '0.00';

	// sanitize
	// ignored

	// prepare vars
	if(empty($cfg_has_cents)) {
		$plan_price = floor($plan_price);
	}

	if($plan_type == 'monthly' || $plan_type == 'monthly_feat') {
		$plan_price = $plan_price . '/' . $txt_month;
	}

	if($plan_type == 'annual' || $plan_type == 'annual_feat') {
		$plan_price = $plan_price . '/';
		$plan_price .= !empty($txt_year) ? $txt_year : 'Year';
	}

	$cur_loop_arr = array(
		'plan_id'           => $plan_id,
		'plan_type'         => $plan_type,
		'plan_name'         => $plan_name,
		'plan_period'       => $plan_period,
		'plan_description1' => $plan_description1,
		'plan_description2' => $plan_description2,
		'plan_description3' => $plan_description3,
		'plan_description4' => $plan_description4,
		'plan_description5' => $plan_description5,
		'plan_price'        => $plan_price
	);

	$plans_arr[] = $cur_loop_arr;
}

/*--------------------------------------------------
max free listings check
--------------------------------------------------*/
if(!isset($cfg_max_free_listings)) {
	$cfg_max_free_listings = 10;
}

$query = "SELECT COUNT(*) AS counter
			FROM places p
			LEFT JOIN plans pl ON p.plan = pl.plan_id
			WHERE pl.plan_type IN('free', 'free_feat') AND userid = :userid";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$free_count = $row['counter'];

// if is admin, set counter to zero so there are no limits
if($is_admin) {
	$free_count = 0;
}

// template file
if(is_file(__DIR__ . '/../templates/user_templates/my_tpl_select-plan.php')) {
	require_once(__DIR__ . '/../templates/user_templates/my_tpl_select-plan.php');
}

else {
	require_once(__DIR__ . '/../templates/user_templates/tpl_select-plan.php');
}