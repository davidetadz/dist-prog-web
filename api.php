<?php

//Include protection
define( 'REQUEST_OK', true );

/* Handle here AJAX requests */

include_once 'include/config.php';
include_once 'include/db.php';
include_once 'include/lib.php';
include_once 'include/session.php';

const getPlaneStatus = 'REQ_PLANE_STATUS';
const bookSeat       = 'REQ_BOOK_SEAT';
const freeSeat       = 'REQ_FREE_SEAT';
const buySeats       = 'REQ_BUY_SEATS';

if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || ! isset( $_POST ) ) {
	api_error( 400, "Bad Request." );
}

if ( $_POST['method'] === 'GET' && $_POST['request'] === getPlaneStatus ) {

	$conn         = db_connect();
	$seats_status = get_bookings_data( $conn );
	$conn->close();

	if ( $seats_status ) {
		api_response( array(
			'plane_width'  => PLANE_WIDTH,
			'plane_length' => PLANE_LENGTH,
			'seats_status' => $seats_status
		) );
	} else {
		// Error while getting current seats booked or bought
		api_error( 503, "Service not available" );
	}
} elseif ( $_POST['method'] === 'POST'
           && ( $_POST['request'] === bookSeat || $_POST['request'] === freeSeat ) ) {
	// Book seat for current user

	if ( isset( $_SESSION['LOGGED_IN'] ) && $_SESSION['LOGGED_IN'] ) {
		// These APIs requires auth

		$request = $_POST['request'];

		if ( isset( $_POST['row'] ) && isset( $_POST['col'] ) ) {

			$conn = db_connect();

			// Protect this transaction
			// Disable autocommit
			$conn->begin_transaction();
			$conn->autocommit( false );

			// Lock table using a SELECT * FOR UPDATE
			if ( ! lock_bookings_table( $conn ) ) {
				$conn->autocommit( true );
				$conn->close();
				api_error( 500, "Internal Server Error." );
			}

			// Get current seat status
			$status = get_seat_status( $conn, $_POST['row'], $_POST['col'] );

			if ( $request === freeSeat ) {
				if ( $status === RES_SEAT_FREE ) // Should not get here!
				{
					$res = RES_SEAT_FREE;
				} else if ( $status === RES_SEAT_BOOKED ) // If booked by myself then free
				{
					if ( free_seat( $conn, $_POST['row'], $_POST['col'] ) === false ) {
						api_error( 500, "Internal Server Error." );
					}
					$res = RES_SEAT_FREE;
				} else if ( $status === RES_SEAT_ALREADY_BOOKED || $status === RES_SEAT_BOUGHT ) {
					// Booked by someone else or bought, cannot free!
					$res = $status;
				}
			} else {    // $request === bookSeat
				if ( $status === RES_SEAT_FREE || $status === RES_SEAT_ALREADY_BOOKED ) {
					// If free or booked by someone else then book
					if ( book_buy_seat( $conn, $_POST['row'], $_POST['col'], $_SESSION['EMAIL'],
							( $status === RES_SEAT_ALREADY_BOOKED ), DB_STATUS_BOOKED ) === false ) {
						api_error( 500, "Internal Server Error." );
					}
					$res = RES_SEAT_BOOKED;
				} else if ( $status === RES_SEAT_BOOKED ) // Should not get here!
				{
					$res = RES_SEAT_BOOKED;
				} else if ( $status === RES_SEAT_BOUGHT ) {
					// Bought, cannot book!
					$res = $status;
				}
			}

			$conn->commit();
			$conn->autocommit( true );
			$conn->close();

			if ( $status === false ) {
				api_error( 400, "Bad Request." );
			}

			api_response( array(
				'result' => $res
			) );

		} else {
			api_error( 400, "Bad Request." );
		}
	} else {
		api_error( 401, "Not Authorized." );
	}
} elseif ( $_POST['method'] === 'POST' && $_POST['request'] === buySeats ) {

	// Buy seats if booked by the user or free

	if ( isset( $_SESSION['LOGGED_IN'] ) && $_SESSION['LOGGED_IN'] ) {
		// This API call requires auth

		if ( isset( $_POST['seats'] ) ) {

			$conn = db_connect();

			// Protect this transaction
			// Disable autocommit
			$conn->begin_transaction();
			$conn->autocommit( false );

			// Lock table using a SELECT * FOR UPDATE
			if ( ! lock_bookings_table( $conn ) ) {
				$conn->rollback();
				$conn->autocommit( true );
				$conn->close();
				api_error( 500, "Internal Server Error." );
			}

			// For each seat, get status
			// If it's free or booked by current user, buy
			// Else rollback whole transaction
			foreach ( $_POST['seats'] as $seat ) {
				// Check if seat is valid
				$col_i = ord( substr( $seat, 0, 1 ) ) - ord( 'A' ) + 1;
				$row_i = substr( $seat, 1, strlen( $seat ) - 1 );

				// Get current seat status
				$status = get_seat_status( $conn, $row_i, $col_i );

				if ( $status === false ) {
					$conn->rollback();
					$conn->autocommit( true );
					$conn->close();
					api_error( 400, "Bad Request." );
				}

				// If it's free or booked by current user, buy
				if ( $status === RES_SEAT_BOOKED || $status === RES_SEAT_FREE ) {
					if ( book_buy_seat( $conn, $row_i, $col_i, $_SESSION['EMAIL'],
							( $status === RES_SEAT_BOOKED ), DB_STATUS_BOUGHT ) === false ) {
						$conn->rollback();
						$conn->autocommit( true );
						$conn->close();
						api_error( 500, "Internal Server Error." );
					}
				} else {
					// If it's been bought or booked by someone else, fail!
					$conn->rollback();
					$conn->autocommit( true );

					// Clear all bookings from this user
					if ( ! free_booked_seats( $conn, $_SESSION['EMAIL'] ) ) {
						$conn->close();

						api_error( 500, "Internal Server Error." );
					}

					$conn->close();

					api_response( array(
						'bought'      => 0,
						'seat'        => $seat,
						'seat_status' => $status
					) );
				}
			}

			// Foreach ended without errors - all seats bought
			// Commit transaction
			$conn->commit();
			$conn->autocommit( true );
			$conn->close();
			api_response( array(
				'bought' => 1
			) );

		} else {
			api_error( 400, "Bad Request." );
		}

	} else {
		api_error( 401, "Not Authorized." );
	}
} else {
	api_error( 400, "Bad Request." );
}