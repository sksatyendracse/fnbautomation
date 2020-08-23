<?php
require_once(__DIR__ . '/../inc/config.php');

// check csrf token
require_once(__DIR__ . '/_user_inc_request_with_ajax.php');

// init vars
$user_exists    = 0;
$user_created   = 0;
$form_submitted = 0;
$empty_fields   = 1;
$invalid_email  = 0;

// initialize swiftmailer
$transport_smtp = (new Swift_SmtpTransport($smtp_server, $smtp_port, $cfg_smtp_encryption))
	->setUsername($smtp_user)
	->setPassword($smtp_pass);

$mailer = new Swift_Mailer($transport_smtp);

// get post data
$params = array();
parse_str($_POST['params'], $params);

// posted vars
$fname    = !empty($params['fname'   ]) ? $params['fname'   ] : '';
$lname    = !empty($params['lname'   ]) ? $params['lname'   ] : '';
$email    = !empty($params['email'   ]) ? $params['email'   ] : '';
$password = !empty($params['password']) ? $params['password'] : '';

// sign up ip
$ip = get_ip();

if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
	// check to see if email already exists
	$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
	$stmt->bindValue(':email', $email);
	$stmt->execute();
	$count = $stmt->fetchColumn();

	// user exists?
	if($count > 0) {
		echo 'user_exists';
		die();
	}

	// else user doesn't exist, so create entry in db
	else {
		// hash
		$password_hashed = password_hash($password, PASSWORD_BCRYPT);

		// insert into db
		$stmt = $conn->prepare('
		INSERT INTO users(
			first_name,
			last_name,
			email,
			password,
			created,
			hybridauth_provider_name,
			ip_addr,
			status
			)
		VALUES(
			:first_name,
			:last_name,
			:email,
			:password,
			NOW(),
			:hybridauth_provider_name,
			:ip,
			:status
			)
		');

		$stmt->bindValue(':first_name'              , $fname);
		$stmt->bindValue(':last_name'               , $lname);
		$stmt->bindValue(':email'                   , $email);
		$stmt->bindValue(':password'                , $password_hashed);
		$stmt->bindValue(':hybridauth_provider_name', 'local');
		$stmt->bindValue(':ip'                      , $ip);
		$stmt->bindValue(':status'                  , 'pending');

		// if query executed fine
		if($stmt->execute()) {
			$user_created = 1;
			$confirm_str = generatePassword(16);
			$user_id = $conn->lastInsertId();

			// insert confirmation string into db
			$stmt2 = $conn->prepare('
			INSERT INTO signup_confirm(
				user_id,
				confirm_str,
				created
				)
			VALUES(
				:user_id,
				:confirm_str,
				NOW()
				)
			');

			$stmt2->bindValue(':user_id'    , $user_id);
			$stmt2->bindValue(':confirm_str', $confirm_str);

			if($stmt2->execute()) {
				// email user
				$query = "SELECT * FROM email_templates WHERE type = 'signup_confirm'";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$email_subject = $row['subject'];
				$email_body    = $row['body'];

				$confirm_link = $baseurl . "/user/signup-confirm/" . $user_id . "," . $confirm_str;
				$email_body = str_replace('%confirm_link%', $confirm_link, $email_body);

				$message = (new Swift_Message())
					->setSubject($email_subject)
					->setFrom(array($admin_email => $site_name))
					->setTo($email)
					->setBody($email_body)
					->setReplyTo($admin_email)
					->setReturnPath($admin_email)
					;

				// Send the message
				if ($mailer->send($message)) {
					echo 'user_created';
				}

				else {
					echo 'mailer_problem';
				}
			}
		}
	}
}

else {
	echo 'invalid_email';
}