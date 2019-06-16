<?php

//Include protection
define('REQUEST_OK', true);

include "include/config.php";
include_once 'include/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	/* Empty _SESSION and destroy */
	$_SESSION = array();
	session_destroy();
	http_response_code( 302 );
	header( 'Location: ' . 'https://' . $_SERVER['HTTP_HOST'] );
} else http_response_code(400);