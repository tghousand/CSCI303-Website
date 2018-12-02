<?php

session_start();
require_once "connect.inc.php";

error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('date.timezone', 'America/New_York');
date_default_timezone_set('America/New_York');

$rightnow = time();
$currentfile = basename($_SERVER['PHP_SELF']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tyler Housand</title>
    <style><link rel="stylesheet" type="text/css" href="styles.css"/></style>
    <link rel="stylesheet" type="text/css" href="styles.css"/>
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=5o7mj88vhvtv3r2c5v5qo4htc088gcb5l913qx5wlrtjn81y"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
</head>
<body>
<header>
    <h1>Final Project - Tyler Housand</h1>
    <nav>
        <?php include_once "nav.inc.php"; ?>
    </nav>
</header>
<main>
    <h2><?php echo $pagename ?></h2>
