<?php
// prevent direct access
if (!isset($version)) {
	http_response_code(403);
	exit;
}

/*--------------------------------------------------
Check update from 1.03 to 1.04
--------------------------------------------------*/

// if 'custom_fields' table doesn't exist, update to v.1.04
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'custom_fields')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$sql = file_get_contents('sql/update_1.03_to_1.04.sql');

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
Check update from 1.04 to 1.05
--------------------------------------------------*/

// if 'contact_msgs' table doesn't exist, update to v.3.13
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'contact_msgs')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);


if($row['c'] < 1) {
	$sql = file_get_contents('sql/update_1.04_to_1.05.sql');

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
Check update from 1.05 to 1.06
--------------------------------------------------*/
// if 'mercadopago_mode' config property doesn't exist
$query = "SELECT count(*) AS c FROM config WHERE property = :property";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':property', 'mercadopago_mode');
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$sql = file_get_contents('sql/update_1.05_to_1.06.sql');

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
Check update from 1.06 to 1.07
--------------------------------------------------*/
$query = "SHOW COLUMNS FROM places LIKE 'last_bump'";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// if table places still has 'last_bump' column, then update to 1.07
if($row['Field'] == 'last_bump') {
	$sql = file_get_contents('sql/update_1.06_to_1.07.sql');

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
Check update from 1.07 to 1.08
--------------------------------------------------*/

// if 'stripe_mode' config property doesn't exist
$query = "SELECT count(*) AS c FROM config WHERE property = :property";
$stmt  = $conn->prepare($query);
$stmt->bindValue(':property', 'stripe_mode');
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$sql = file_get_contents('sql/update_1.07_to_1.08.sql');

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
Check update from 1.10 to 1.11
--------------------------------------------------*/
// if 'coupons' table doesn't exist, update to v.1.11
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'coupons')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['c'] < 1) {
	$sql = file_get_contents('sql/update_1.10_to_1.11.sql');

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}
