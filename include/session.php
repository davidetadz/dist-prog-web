<?php

if (!defined('REQUEST_OK'))
	exit();

session_start([
	'cookie_secure' => true
]);

/* Check Timeout of 2 minutes */
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
	// last request was more than 2 minutes ago
	$_SESSION = array();
	session_destroy();
	session_start();
}

// Update last activity timestamp
$_SESSION['LAST_ACTIVITY'] = time();