<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 12:31 PM
 */

$pagename = "Authentication Confirmation";
require_once "header.inc.php";

if($_GET['state'] == 1){
    echo "<p>Logout confirmed. Please <a href='login.php'>log in</a> again to view restricted content.</p>";
}elseif ($_GET['state'] == 2){
    echo "<p>Welcome back, " . $_SESSION['username'] . "!</p>";
}else{
    echo "<p>Please continue by choosing an item from the menu.</p>";
}

require_once "footer.inc.php";
?>