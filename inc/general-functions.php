<?php
// returns error string for PHP's error codes
// see: http://php.net/manual/en/features.file-upload.errors.php
function file_upload_errors($code) {
	if(in_array($code, array(0, 1, 2, 3, 4, 5, 6, 7, 8))) {
		$arr = array(
			0 => 'There is no error, the file uploaded with success',
			1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
			2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
			3 => 'The uploaded file was only partially uploaded',
			4 => 'No file was uploaded',
			6 => 'Missing a temporary folder',
			7 => 'Failed to write file to disk.',
			8 => 'A PHP extension stopped the file upload.',
		);

		return $arr[$code];
	}

	return;
}