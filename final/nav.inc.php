<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 1/30/2018
 * Time: 11:01 AM
 */
$currentfile = basename($_SERVER['PHP_SELF']);
?>
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href='registration.php'>User Registration</a></li>
        <?php
        if(isset($_SESSION['username'])){echo "<li><a href='userlist.php'>User List</a></li>";}
        ?>
        <?php
        if(isset($_SESSION['username'])){echo "<li><a href='contentlist.php'>Content List</a></li>";}
        ?>
        <?php
        if(isset($_SESSION['username'])){echo "<li><a href='search.php'>Search Content</a></li>";}
        ?>
        <?php
            echo (isset($_SESSION['username'])) ? "<li><a href='logout.php'>Logout</a></li>" : "<li><a href='login.php'>Log In</a></li>";
        ?>
    </ul>
</nav>