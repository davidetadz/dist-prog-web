<?php
global $title;
global $page_name;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <link rel="stylesheet" type="text/css" href="../css/general.css">

    <?php
    // Load page-specific style
    echo '<link rel="stylesheet" type="text/css" href="../css/' . $page_name .'.css">';
    ?>

    <script type="text/javascript" lang="js" src="../js/jquery-3.4.1.js"></script>
    <script type="text/javascript" lang="js" src="../js/lib.js"></script>

	<?php
	// Load page-specific script
	echo '<script type="text/javascript" lang="js" src="../js/' . $page_name . '.js"></script>';
	?>

</head>

<body>
<header>
    <h1 id="site-name">AirPoli</h1>
</header>