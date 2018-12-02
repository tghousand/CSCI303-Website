<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 12:46 PM
 */

session_start();
session_unset();
session_destroy();
header("Location: confirm.php?state=1");
exit();