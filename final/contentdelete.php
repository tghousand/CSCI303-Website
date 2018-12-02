<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 4/11/2018
 * Time: 11:04 PM
 */

$pagename = "Delete Class";
include_once "header.inc.php";

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ID'])){
    $id = $_GET['ID'];
    try {
        $sql = "SELECT userID FROM tghousandcontent WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $_GET['ID']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ID'])){
    $id = $_POST['ID'];
    try {
        $sql = "SELECT userID FROM tghousandcontent WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $_POST['ID']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

if($_SESSION['ID'] != $row['userID']){
    echo "Access denied.";
}else {

    $showform = 1;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $sql = "DELETE FROM tghousandcontent WHERE ID = :ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':ID', $_POST['ID']);
            $stmt->execute();
            $showform = 0;

            echo "<p>Item deleted. <a href='contentlist.php'>Return to list? </a></p>";
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    if ($showform == 1) {
        ?>
        <p>Are you sure you want to delete <?php echo $_GET['C']; ?>?</p>
        <form id="delete" name="delete" method="post" action="contentdelete.php">
            <input type="hidden" id="ID" name="ID" value="<?php echo $_GET['ID']; ?>"/>
            <input type="hidden" id="Category" name="Category" value="<?php echo $_GET['C']; ?>"/>
            <input type="submit" id="delete" name="delete" value="YES"/>
            <input type="button" id="nodelete" name="nodelete" value="NO" onClick="window.location='contentlist.php'"/>
        </form>
        <?php
    }
}
include_once "footer.inc.php";
?>