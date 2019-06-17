<?php

if (!defined('REQUEST_OK'))
	exit();

global $title;
global $page_name;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <link rel="stylesheet" type="text/css" href="<?= './css/general.css'?>">

    <?php
    // Load page-specific style
    echo '<link rel="stylesheet" type="text/css" href="./css/' . $page_name .'.css">';
    ?>

    <script type="text/javascript" lang="js" src="./js/jquery-3.4.1.js"></script>
    <script type="text/javascript" lang="js" src="./js/lib.js"></script>

	<?php
	// Load page-specific script
	echo '<script type="text/javascript" lang="js" src="./js/' . $page_name . '.js"></script>';
	?>

</head>

<body>
<header>
    <h1 id="site-name">AirPoli</h1>
    <?php if(isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN']) { ?>

        <p id="logged-user">
	        <svg version="1.1" viewBox="0 0 12.7 12.7" xmlns="http://www.w3.org/2000/svg">
                <g transform="translate(0 -284.3)">
                    <path d="m6.3492 285.36c-2.9194 0-5.2911 2.3733-5.2911 5.2927 0 2.9194 2.3718 5.2912 5.2911 5.2912 2.9194 0 5.2927-2.3718 5.2927-5.2912 0-2.9194-2.3733-5.2927-5.2927-5.2927zm0 0.52917c2.6334 0 4.7635 2.1301 4.7635 4.7635s-2.1301 4.762-4.7635 4.762c-2.6334 0-4.762-2.1286-4.762-4.762s2.1286-4.7635 4.762-4.7635zm0.00101 2.1177c-0.72745 0-1.3224 0.59495-1.3224 1.3224s0.59495 1.3219 1.3224 1.3219c0.72744 0 1.3224-0.59443 1.3224-1.3219s-0.59495-1.3224-1.3224-1.3224zm0 0.52917c0.44155 0 0.79323 0.35168 0.79323 0.79323 0 0.44156-0.35168 0.79479-0.79323 0.79479-0.44156 0-0.7953-0.35323-0.7953-0.79479 0-0.0552 6e-3 -0.10936 0.016499-0.16123 0.0737-0.36307 0.39241-0.632 0.77877-0.632zm0 2.3807c-1.1681 0-2.1167 0.94861-2.1167 2.1167a0.26461 0.26461 0 0 0 0.26406 0.26459h3.7047a0.26461 0.26461 0 0 0 0.26406 -0.26459c0-1.1681-0.9481-2.1167-2.1161-2.1167zm0 0.52918c0.78989 0 1.4062 0.57267 1.5348 1.3229h-3.0696c0.12858-0.75023 0.74489-1.3229 1.5348-1.3229z" color="#000000" color-rendering="auto" dominant-baseline="auto" image-rendering="auto" shape-rendering="auto" solid-color="#000000"></path>
                </g>
            </svg>
            <?= $_SESSION['EMAIL'] ?>
        </p>

    <?php } ?>
</header>