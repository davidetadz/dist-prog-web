<?php

if ( ! defined( 'REQUEST_OK' ) ) {
	exit();
}

include_once 'config.php';
include_once 'lib.php';

function db_connect() {

	$conn = mysqli_connect( DB_URL, DB_USER, DB_PASSWORD, DB_NAME );

	if ( ! $conn ) {
		critical_error( APP_CONN_ERR, null,
			mysqli_connect_errno(), mysqli_connect_error() );
	}

	return $conn;
}

function get_bookings_data( mysqli $conn ) {

	$res = $conn->query( "SELECT * FROM " . DB_BOOKINGS_TABLE );

	// Check if query successful
	if ( $res ) {

		$seats_status          = array();
		$seats_status["count"] = mysqli_num_rows( $res );

		// Get a row at a time
		while ( $row = $res->fetch_assoc() ) {

			// If row booked
			if ( intval( $row['status'] ) === DB_STATUS_BOOKED ) {

				// Check if row is booked by current user
				if ( isset( $_SESSION['LOGGED_IN'] ) && $_SESSION['LOGGED_IN']
				     && $row['user'] === $_SESSION['EMAIL'] ) {

					$status = RES_SEAT_BOOKED;

				} else // Or booked by another user - or user not logged in
				{
					$status = RES_SEAT_ALREADY_BOOKED;
				}
			} else // Row bought
			{
				$status = RES_SEAT_BOUGHT;
			}

			$seats_status[ $row['row'] ][ $row['letter'] ] = $status;
		}

		$res->free();

		return $seats_status;

	} else {
		return false;
	}

}

function get_seat_status( mysqli $conn, $row_i, $col_i ) {

	/* intval returns 0 if an error occurred */
	$row_i = intval( $row_i );
	$col_i = intval( $col_i );

	if ( $row_i <= 0 || $row_i > PLANE_LENGTH || $col_i <= 0 || $col_i > PLANE_WIDTH ) {
		return false;
	}

	$stmt = $conn->prepare( "SELECT COUNT(*), status, user FROM " . DB_BOOKINGS_TABLE . " WHERE row=? AND letter=?" );

	if ( $stmt ) {

		$stmt->bind_param( "ii", $row_i, $col_i );

		if ( $stmt->execute() ) {

			$stmt->bind_result( $count, $row_status, $row_email );

			$stmt->fetch();

			if ( $count === 0 ) {
				$stmt->close();

				return RES_SEAT_FREE;
			}

			if ( $row_status === DB_STATUS_BOOKED ) {
				if ( isset( $_SESSION['LOGGED_IN'] ) && $_SESSION['LOGGED_IN']
				     && $row_email === $_SESSION['EMAIL'] ) {
					return RES_SEAT_BOOKED;
				} else {
					return RES_SEAT_ALREADY_BOOKED;
				}
			} else if ( $row_status === DB_STATUS_BOUGHT ) {
				return RES_SEAT_BOUGHT;
			} else {
				return false;
			}

		} else {
			return false;
		}

	} else {
		return false;
	}

}

function free_seat( mysqli $conn, $row_i, $col_i ) {
	/* intval returns 0 if an error occurred */
	$row_i = intval( $row_i );
	$col_i = intval( $col_i );

	if ( $row_i <= 0 || $row_i > PLANE_LENGTH || $col_i <= 0 || $col_i > PLANE_WIDTH ) {
		return false;
	}

	$stmt = $conn->prepare( "DELETE FROM " . DB_BOOKINGS_TABLE . " WHERE row=? AND letter=?" );

	if ( $stmt ) {

		$stmt->bind_param( "ii", $row_i, $col_i );

		if ( $stmt->execute() ) {

			$stmt->close();

			return true;

		} else {
			$stmt->close();

			return false;
		}

	} else {
		return false;
	}
}

function book_buy_seat( mysqli $conn, $row_i, $col_i, $user, $row_present, $status ) {
	/* intval returns 0 if an error occurred */
	$row_i = intval( $row_i );
	$col_i = intval( $col_i );

	if ( $row_i <= 0 || $row_i > PLANE_LENGTH || $col_i <= 0 || $col_i > PLANE_WIDTH ) {
		return false;
	}

	// If row present then UPDATE, otherwise INSERT
	if ( $row_present ) {
		$stmt = $conn->prepare( "UPDATE " . DB_BOOKINGS_TABLE . " SET user=?, status=? WHERE row=? AND letter=?" );
	} else {
		$stmt = $conn->prepare( "INSERT INTO " . DB_BOOKINGS_TABLE . " (user, status, row, letter) VALUES (?, ?, ?, ?)" );
	}

	if ( $stmt ) {

		// Used intval() because DB_STATUS_BOOKED is a constant and cannot be passed to bind_param
		$stmt->bind_param( "siii", $user, intval( $status ), $row_i, $col_i );

		if ( $stmt->execute() ) {

			$stmt->close();

			return true;

		} else {
			$stmt->close();

			return false;
		}

	} else {
		return false;
	}

}

function buy_seat( mysqli $conn, $row_i, $col_i, $user, $row_present ) {

}

function lock_bookings_table( mysqli $conn ) {
	return $conn->query( "SELECT * FROM " . DB_BOOKINGS_TABLE . " FOR UPDATE" );
}

function create_user( mysqli $conn, $username, $password ) {

	/* Disable AUTOCOMMIT to start a transaction */
	//mysqli_autocommit($conn, false);

	/* Generate SALT, prepend SALT to password and hash result string
		PASSWORD_DEFAULT    bcrypt with strong salt and 60 chars
	SALT is part of returned hash so no need to store in a separate column */
	$hashed = password_hash( $password, PASSWORD_DEFAULT );

	/* USE IN BOOKINGS INSERT
	   Begin a SELECT [...] FOR UPDATE in order to lock db
		and check if user already exists
	   If not, add the new user and hashed password to the DB
	$conn->query('SELECT * FROM ' . DB_USERS_TABLE . ' FOR UPDATE'); */

	$stmt = $conn->prepare( "INSERT INTO " . DB_USERS_TABLE . " (email, password) VALUES (?, ?)" );

	if ( $stmt ) {

		$stmt->bind_param( "ss", $username, $hashed );

		if ( $stmt->execute() ) {
			$stmt->close();

			return true;
		} else {
			$stmt->close();

			return false;
		}

	} else {
		critical_error( "Errore nell'aggiunta di un utente", $conn );

		return false;
	}

	/* ROLLBACK o COMMIT */

	//mysqli_autocommit($conn, true);
}

function check_user( mysqli $conn, $username, $password ) {

	$stmt = $conn->prepare( "SELECT email, password FROM " . DB_USERS_TABLE . " WHERE email=?" );

	if ( $stmt ) {
		$stmt->bind_param( "s", $username );

		if ( $stmt->execute() ) {

			$stmt->bind_result( $email_res, $password_res );

			$stmt->fetch();

			if ( strcmp( $email_res, $username ) === 0 && password_verify( $password, $password_res ) ) {
				$stmt->close();

				return true;
			} else {
				$stmt->close();

				return false;
			}
		} else {
			$stmt->close();

			return false;
		}
	} else {
		critical_error( "Errore nell'aggiunta di un utente", $conn );

		return false;
	}

}

function check_email( $email ) {
	if ( preg_match( EMAIL_REGEX, $email ) === 1 ) {
		return true;
	} else {
		return false;
	}
}

function check_password( $password ) {
	if ( preg_match( PASSWORD_REGEX_1, $password ) === 1 && preg_match( PASSWORD_REGEX_2, $password ) === 1 ) {
		return true;
	} else {
		return false;
	}
}