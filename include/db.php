<?php

include_once 'config.php';
include_once 'lib.php';

$conn = mysqli_connect(DB_URL, DB_USER, DB_PASSWORD, DB_NAME);

if (!$conn) {
	critical_error('Errore nello stabilire una connessione con il database.',
	mysqli_connect_errno(), mysqli_connect_error());
}

/* Check plane size in DB */


function create_user($username, $password) {

	/* Generate SALT, prepend SALT to password and hash result string
		PASSWORD_DEFAULT    bcrypt with strong salt and 60 chars
	SALT is part of returned hash so no need to store in a separate column */
	$hashed = password_hash($password, PASSWORD_DEFAULT);



}

function check_email() {

}

function check_password() {

}