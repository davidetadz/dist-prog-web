<?php

include_once 'config.php';

/* Handle critical errors / exceptions */

function critical_error($message, $error_n = null, $error_desc = null) {
	$message .= '<br/><br/>';

	if ($error_n != null)
          $message .= 'Error N. ' . $error_n . '<br/>';

	if ($error_desc != null)
		$message .= 'Error Description: ' . mysqli_connect_error();

	$title = 'Error - AirPoli';

	include(__DIR__ . '/../fragments/head_tags.php');
	echo '<div id="error-box">' . $message . '</div>';
	include(__DIR__ . '/../fragments/footer.php');
	exit();
}

/* API useful functions to format output */

function api_error($code, $message) {
	http_response_code($code);
	header('Content-Type: application/json');

	echo json_encode(array(
		'status'    =>  $code,
		'message'   => $message
	));

	exit();
}

function api_response($data) {
	http_response_code(200);
	header('Content-Type: application/json');

	echo json_encode(array(
		'status'    =>  200,
		'message'   =>  'success',
		'data'      =>  $data
	));

	exit();
}