<?php
require_once __DIR__ . '/../../inc/config.php';
require_once __DIR__ . '/translation.php';
require_once __DIR__ . '/../../vendor/swiftmailer/swiftmailer/lib/swift_required.php';

// initialize swiftmailer
$transport_smtp = (new Swift_SmtpTransport($smtp_server, $smtp_port, $cfg_smtp_encryption))
	->setUsername($smtp_user)
	->setPassword($smtp_pass);

$mailer = new Swift_Mailer($transport_smtp);

// posted vars
$place_id      = (!empty($_POST['place_id'     ])) ? $_POST['place_id'     ] : '';
$sender_email  = (!empty($_POST['sender_email' ])) ? $_POST['sender_email' ] : '';
$sender_msg    = (!empty($_POST['sender_msg'   ])) ? $_POST['sender_msg'   ] : '';
$verify_answer = (!empty($_POST['verify_answer'])) ? $_POST['verify_answer'] : '';

// sender ip
$sender_ip = get_ip();

// check if sender ip already submitted less than 10 secs ago
$query = "SELECT TIMESTAMPDIFF(SECOND, created, NOW()) AS secs_ago FROM contact_msgs WHERE sender_ip = :sender_ip ORDER BY created DESC LIMIT 1";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':sender_ip', $sender_ip);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$secs_ago = (!empty($row['secs_ago'])) ? $row['secs_ago'] : '10';

if($secs_ago < 10) {
	echo '<div class="alert alert-danger alert-dismissible contact-owner-alert">', $txt_contact_owner_please_wait, '</div>';
	return;
}

// get plugin settings from db
$query = "SELECT * FROM config WHERE property = :plugin_contact_owner";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':plugin_contact_owner', 'plugin_contact_owner');
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$plugin_values = $row['value'];
$plugin_values = unserialize($plugin_values);

$answer        = (!empty($plugin_values['answer']))        ? $plugin_values['answer']        : 'a';
$email_subject = (!empty($plugin_values['email_subject'])) ? $plugin_values['email_subject'] : '';

// get listing owner email
$query = "SELECT
			u.email
			FROM places p
			LEFT JOIN users u ON p.userid = u.id
			WHERE p.place_id = :place_id";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':place_id', $place_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$listing_owner_email = $row['email'];

// send message
if($verify_answer == $answer) {
	if (filter_var($sender_email, FILTER_VALIDATE_EMAIL) && filter_var($listing_owner_email, FILTER_VALIDATE_EMAIL)) {
		$message = (new Swift_Message())
			->setSubject($email_subject)
			->setFrom(array($sender_email => $site_name))
			->setTo($listing_owner_email)
			->setBody($sender_msg)
			->setReplyTo($sender_email)
			->setReturnPath($admin_email)
			;

		// Send the message
		if($mailer->send($message)) {
			// write to db
			$query = "INSERT INTO contact_msgs(
						sender_email,
						sender_ip,
						place_id,
						msg)
				VALUES(
						:sender_email,
						:sender_ip,
						:place_id,
						:msg)";

			$stmt = $conn->prepare($query);
			$stmt->bindValue(':sender_email', $sender_email);
			$stmt->bindValue(':sender_ip',    $sender_ip);
			$stmt->bindValue(':place_id',     $place_id);
			$stmt->bindValue(':msg',          $sender_msg);
			$stmt->execute();
			?>
			<div class="alert alert-success alert-dismissible contact-owner-alert"><?= $txt_contact_owner_msg_sent; ?></div>
			<?php
		} else {
			?>
			<div class="alert alert-danger alert-dismissible contact-owner-alert">
				error sending message
			</div>
			<?php
		}
	} else {
		?>
		<div class="alert alert-success alert-dismissible contact-owner-alert">
			<button type="button" class="close contact-owner-dismiss-alert" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

			<?= $txt_contact_owner_invalid_email; ?>
		</div>
		<?php
	}
} else {
	?>
	<div class="alert alert-warning alert-dismissible contact-owner-alert">
		<button type="button" class="close contact-owner-dismiss-alert" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<?= $txt_contact_owner_invalid_answer; ?>
	</div>
	<?php
}