<?php

//Include protection
define('REQUEST_OK', true);

include_once 'include/config.php';
include_once 'include/db.php';
include_once 'include/session.php';

// Redirect to personal home if already logged in
if  (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN']) {
	http_response_code( 302 );
	header( 'Location: ' . 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)
    && isset($_POST['email']) && isset($_POST['password'])
    && isset($_POST['submit']) && strcmp($_POST['submit'], 'Registrati') === 0) {

    /* Check username and password - sanitize email */
    $email = strip_tags(htmlspecialchars($_POST['email']));
    $password = $_POST['password'];

    if (check_email($email) && check_password($password)){

        // Data OK - add in DB and redirect to home page
        $conn = db_connect();
        $res = create_user($conn, $email, $password);
        $conn->close();

        if ($res) {
            // In order to prevent someone to reuse an older PHPSESSID
	        //  regenerate a new ID after a new login
	        // Empty and destroy current session
	        $_SESSION = array();
	        session_destroy();
	        // Start a new session and generate a new ID
	        session_start();
	        session_regenerate_id(true);
            $_SESSION['LOGGED_IN'] = true;
            $_SESSION['EMAIL'] = $email;
	        http_response_code( 302 );
	        header( 'Location: ' . 'https://' . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['REQUEST_URI'] ));
        } else {
            // User already registered - error while inserting data into DB
            $error_msg = 'Esiste già un utente associato a questo indirizzo email.';
        }
    } else {
	    // Error while checking data
	    $error_msg = 'Email e/o password non valide.';
    }
}

/* Define page variable */
$title     = 'Registrati - AirPoli';
$page_name = 'register';

/* Insert head tags */
include( 'fragments/head_tags.php' );

/* Insert page header */
include( 'fragments/nav.php' );

?>

    <div id="register-container">
        <form method="post" action="register.php">
            <?php
            if (isset($error_msg)) {
            ?>

                <div class="input-container">
                    <p class="error"><?= $error_msg ?></p>
                </div>

            <?php
            }
            ?>
            <div class="input-container">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-container">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="btn-container">
                <input type="submit" value="Registrati" name="submit">
            </div>
        </form>
    </div>

<?php

include( 'fragments/footer.php' );