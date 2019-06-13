<?php

include_once 'include/config.php';
include_once 'include/db.php';
include_once 'include/lib.php';
include_once 'include/session.php';

/* Define page variable */
$title = 'Home - AirPoli';
$page_name = 'index';

/* Insert head tags */
include('fragments/head_tags.php');

/* Insert page navigation */
include( 'fragments/nav.php' );

?>

<div id="seats-map-container">
	<table id="seats-map" <?= $logged_in ? 'class="auth"' : '' ?>></table>
    <div id="counters">
        <p>Posti Totali: <span id="total-n"></span></p>
        <p>Posti Acquistati: <span id="bought-n"></span></p>
        <p>Posti Prenotati: <span id="booked-n"></span></p>
        <p>Posti Liberi: <span id="free-n"></span></p>
    </div>
</div>

<?php

/* TODO: Add seats counters */




include('fragments/footer.php');