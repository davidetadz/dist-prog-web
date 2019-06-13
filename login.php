<?php

include_once 'include/config.php';
include_once 'include/db.php';
include_once 'include/session.php';

/* Define page variable */
$title     = 'Accedi - AirPoli';
$page_name = 'login';

/* Insert head tags */
include( 'fragments/head_tags.php' );

/* Insert page header */
include( 'fragments/nav.php' );

?>

    <div id="login-container">
        <form>
            <div class="input-container">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="input-container">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="btn-container">
                <input type="submit" value="Accedi" name="submit">
            </div>
        </form>
    </div>

<?php

include( 'fragments/footer.php' );