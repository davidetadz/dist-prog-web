<?php

$logged_in = false;

?>

<nav>
    <div id="menu-container">

        <a href="/index.php" class="menu-item <?= ($page_name == 'index') ? 'active' : ''?>">Home</a>
        <a href="#" class="menu-item" onclick="document.location.reload()">Aggiorna</a>

		<?php if ( !$logged_in ) {

			?>

            <a href="/login.php" class="menu-item spaced <?= ($page_name == 'login') ? 'active' : ''?>">Accedi</a>
            <a href="/register.php" class="menu-item <?= ($page_name == 'register') ? 'active' : ''?>">Registrati</a>

		<?php } else { ?>

			<a class="menu-item spaced">Esci</a>

		<?php } ?>

    </div>
</nav>