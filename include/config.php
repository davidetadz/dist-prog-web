<?php

const DB_URL = '127.0.0.1';
const DB_NAME = 'distprog';
const DB_USER = 'root';
const DB_PASSWORD = '';

const PLANE_WIDTH_NAME = 'PLANE_WIDTH';
const PLANE_LENGTH_NAME = 'PLANE_LENGTH';

const PLANE_WIDTH = 6;
const PLANE_LENGTH = 10;

const SALT_LEN = 32;

if ( empty( $_SERVER['HTTPS'] ) || $_SERVER['HTTPS'] === "off" ) {
	// Redirect HTTPS

	$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $redirect);

	//TODO: Destroy session and cookie

	exit();
}