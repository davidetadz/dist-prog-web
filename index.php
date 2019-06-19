<?php

//Include protection
define( 'REQUEST_OK', true );

include_once 'include/config.php';
include_once 'include/db.php';
include_once 'include/lib.php';
include_once 'include/session.php';

/* Define page variable */
$title     = 'Home - AirPoli';
$page_name = 'index';

/* Insert head tags */
include( 'fragments/head_tags.php' );

/* Insert page navigation */
include( 'fragments/nav.php' );

?>

    <div id="seats-map-container">
        <table id="seats-map" <?= ( isset( $_SESSION['LOGGED_IN'] ) && $_SESSION['LOGGED_IN'] ) ? 'class="auth"' : '' ?>></table>
        <div id="status-container">
            <?php if (!isset($_SESSION['LOGGED_IN']) || !$_SESSION['LOGGED_IN']) { ?>
            <div id="counters">
                <p>Posti Totali: <span id="total-n"></span></p>
                <p>Posti Acquistati: <span id="bought-n"></span></p>
                <p>Posti Prenotati: <span id="booked-n"></span></p>
                <p>Posti Liberi: <span id="free-n"></span></p>
            </div>
            <?php } ?>
            <p id="status-header">
                <svg version="1.1" viewBox="0 0 46 46" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" fill-rule="evenodd">
                        <g transform="translate(-7 -7)" fill="#000">
                            <g transform="translate(7 7)">
                                <g transform="translate(7.6667 7.6667)">
                                    <path d="m15.333 7.6667c1.0166 0 1.84 0.8234 1.84 1.84 0 1.0166-0.8234 1.84-1.84 1.84s-1.84-0.8234-1.84-1.84c0-1.0166 0.8234-1.84 1.84-1.84zm-1.5333 15.333v-10.733h3.0667v10.733h-3.0667zm1.5333 4.6c6.7635 0 12.267-5.5031 12.267-12.267 0-6.7635-5.5031-12.267-12.267-12.267-6.7635 0-12.267 5.5031-12.267 12.267 0 6.7635 5.5031 12.267 12.267 12.267zm0-27.6c8.4671 0 15.333 6.8647 15.333 15.333 0 8.4686-6.8663 15.333-15.333 15.333-8.4671 0-15.333-6.8647-15.333-15.333 0-8.4686 6.8663-15.333 15.333-15.333z"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>

                <span>Stato</span>
            </p>
            <p id="status"></p>
        </div>
    </div>

<?php

include( 'fragments/footer.php' );