<?php

/* Handle here AJAX requests */

include_once 'include/lib.php';

const getPlaneSize = 'REQ_PLANE_SIZE';

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST))
	api_error(400, "Bad Request.");

if ($_POST['method'] == 'GET' && $_POST['request'] == getPlaneSize) {
	api_response(array(
		'plane_width'   =>  PLANE_WIDTH,
		'plane_length'  =>  PLANE_LENGTH
	));
} else
	api_error(400, "Bad Request.");