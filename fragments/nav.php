<?php

if (!defined('REQUEST_OK'))
	exit();

?>
<nav>
    <div id="menu-container">

        <a href="/index.php" class="menu-item <?= ($page_name == 'index') ? 'active' : ''?>">Home</a>
        <a href="#" class="menu-item" onclick="document.location.reload()">Aggiorna</a>

		<?php if ( !(isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN']) ) {

			?>

            <a href="/login.php" class="menu-item spaced <?= ($page_name == 'login') ? 'active' : ''?>">Accedi</a>
            <a href="/register.php" class="menu-item <?= ($page_name == 'register') ? 'active' : ''?>">Registrati</a>

		<?php } else { ?>

            <a class="menu-item" onclick="buySeats()">Acquista</a>
			<a class="menu-item spaced" href="/logout.php">Esci</a>

		<?php } ?>

    </div>
</nav>