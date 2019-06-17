<?php

if (!defined('REQUEST_OK'))
	exit();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

/* Database Configuration */

const DB_URL = 'localhost';
const DB_NAME = 's267612';
const DB_USER = 's267612';
const DB_PASSWORD = 'rtskinah';

const DB_USERS_TABLE = 'users';
const DB_BOOKINGS_TABLE = 'bookings';

const DB_STATUS_BOOKED = 1;
const DB_STATUS_BOUGHT = 2;

/* App Configuration */

const PLANE_WIDTH_NAME = 'PLANE_WIDTH';
const PLANE_LENGTH_NAME = 'PLANE_LENGTH';

const PLANE_WIDTH = 6;
const PLANE_LENGTH = 10;

const APP_CONFIG = [
	PLANE_WIDTH_NAME => PLANE_WIDTH,
	PLANE_LENGTH_NAME => PLANE_LENGTH
];

const RES_SEAT_BOOKED = 0;
const RES_SEAT_FREE = 1;
const RES_SEAT_ALREADY_BOOKED = 2;
const RES_SEAT_BOUGHT = 3;

/* Errors */
const APP_CONN_ERR = 'Errore nello stabilire una connessione con il database.';
const APP_SAVE_CONF_ERR = 'Errore nel salvataggio della configurazione.';

/* User Data constants */
const SALT_LEN = 32;
const EMAIL_REGEX = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+$/';
const PASSWORD_REGEX_1 = '/([a-z]+)/';
const PASSWORD_REGEX_2 = '/([0-9A-Z]+)/';

if ( empty( $_SERVER['HTTPS'] ) || $_SERVER['HTTPS'] === "off" ) {
	// Redirect HTTPS

	$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $redirect);

	exit();
}