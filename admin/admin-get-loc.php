<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php');
require_once($lang_folder . '/admin_translations/trans-locations.php');

// csrf check
require_once(__DIR__ . '/_admin_inc_request_with_ajax.php');

$loc_id = $_POST['loc_id'];
$loc_type = $_POST['loc_type'];

// if editing city
if($loc_type == 'city') {
	$query = "SELECT * FROM cities WHERE city_id = :city_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':city_id', $loc_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$city_name = !empty($row['city_name']) ? $row['city_name'] : '';
	$state     = !empty($row['state'    ]) ? $row['state'    ] : '';
	$state_id  = !empty($row['state_id' ]) ? $row['state_id' ] : '';
	$lat       = !empty($row['lat'      ]) ? $row['lat'      ] : '';
	$lng       = !empty($row['lng'      ]) ? $row['lng'      ] : '';
	?>
	<input type="hidden" id="loc_type" name="loc_type" value="city">
	<input type="hidden" id="loc_id" name="loc_id" value="<?= $loc_id ?>">

	<div class="form-group">
		<label class="label" for="city_name"><?= $txt_city_name ?></label>
		<input type="text" id="city_name" class="form-control" name="city_name" value="<?= $city_name ?>" required>
	</div>

	<div class="form-group">
		<label class="label" for="state"><?= $txt_select_state ?></label>
		<select id="state" class="form-control" name="state" required>
			<?php
			// count states
			$query = "SELECT COUNT(*) AS total_rows FROM states";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$total_rows = $row['total_rows'];

			if($total_rows > 0) {
				// select all states
				$query = "SELECT * FROM states ORDER BY state_name";
				$stmt = $conn->prepare($query);
				$stmt->execute();

				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$this_state_id   = $row['state_id'];
					$this_state_name = $row['state_name'];
					$this_state_abbr = e($row['state_abbr']);

					$value = "$this_state_id,$this_state_abbr";
					?>
					<option value="<?= $value ?>" <?= $state_id == $this_state_id ? 'selected' : '' ?>><?= $this_state_name ?></option>
					<?php
				}
			}

			else {
				?>
				<option value=""><?= $txt_create_state ?></option>
				<?php
			}
			?>
		</select>
	</div>

	<div class="form-group">
		<label class="label" for="lat"><?= $txt_lat ?></label>
		<input type="text" id="lat" class="form-control" name="lat" value="<?= $lat ?>" required>
	</div>

	<div class="form-group">
		<label class="label" for="lng"><?= $txt_lng ?></label>
		<input type="text" id="lng" class="form-control" name="lng" value="<?= $lng ?>" required>
	</div>
	<?php
}

// if editing state
if($loc_type == 'state') {
	$query = "SELECT *, slug AS state_slug FROM states WHERE state_id = :state_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':state_id', $loc_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$state_name   = !empty($row['state_name'  ]) ? $row['state_name'  ] : '';
	$state_abbr   = !empty($row['state_abbr'  ]) ? $row['state_abbr'  ] : '';
	$state_slug   = !empty($row['state_slug'  ]) ? $row['state_slug'  ] : '';
	$country_abbr = !empty($row['country_abbr']) ? $row['country_abbr'] : '';
	$country_id   = !empty($row['country_id'  ]) ? $row['country_id'  ] : '';
	?>
	<input type="hidden" id="loc_type" name="loc_type" value="state">
	<input type="hidden" id="loc_id" name="loc_id" value="<?= $loc_id ?>">

	<div class="form-group">
		<label class="label" for="state_name"><?= $txt_state_name ?></label>
		<input type="text" id="state_name" class="form-control" name="state_name" value="<?= $state_name ?>" required>
	</div>

	<div class="form-group">
		<label class="label" for="state_abbr"><?= $txt_state_abbr ?></label>
		<input type="text" id="state_abbr" class="form-control" name="state_abbr" value="<?= $state_abbr ?>" required>
	</div>

	<div class="form-group">
		<label class="label" for="country"><?= $txt_select_country ?></label>
		<select id="country" class="form-control" name="country" required>
			<?php
			// count states
			$query = "SELECT COUNT(*) AS total_rows FROM countries";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$total_rows = $row['total_rows'];

			if($total_rows > 0) {
				// select all states
				$query = "SELECT * FROM countries ORDER BY country_name";
				$stmt = $conn->prepare($query);
				$stmt->execute();

				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$this_country_id   = $row['country_id'];
					$this_country_name = $row['country_name'];
					$this_country_abbr = $row['country_abbr'];

					$value = "$country_id,$country_abbr";
					?>
					<option value="<?= $value ?>" <?= $country_id == $this_country_id ? 'selected' : '' ?>><?= $this_country_name ?></option>
					<?php
				}
			}
			else {
				?>
				<option value=""><?= $txt_create_country ?></option>
				<?php
			}
			?>
		</select>
	</div>
	<?php
}

// if editing country
if($loc_type == 'country') {
	$query = "SELECT * FROM countries WHERE country_id = :country_id";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':country_id', $loc_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$country_name = !empty($row['country_name']) ? $row['country_name'] : '';
	$country_abbr = !empty($row['country_abbr']) ? $row['country_abbr'] : '';
	?>
	<input type="hidden" id="loc_type" name="loc_type" value="country">
	<input type="hidden" id="loc_id" name="loc_id" value="<?= $loc_id ?>">
	<div class="form-group">
		<label class="label" for="country_name"><?= $txt_country_name ?></label>
		<input type="text" id="country_name" class="form-control" name="country_name" value="<?= $country_name ?>" required>
	</div>

	<div class="form-group">
		<label class="label" for="country_abbr"><?= $txt_country_abbr ?></label>
		<input type="text" id="country_abbr" class="form-control" name="country_abbr" value="<?= $country_abbr ?>" required>
	</div>
	<?php
}