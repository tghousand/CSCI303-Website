<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 2:24 PM
 */
$pagename = "User List";
include_once "header.inc.php";

if(!isset($_SESSION['username'])){
    echo "Access denied. Please <a href='login.php'>log in</a> to view content.";
}else {
    try {
        $sql = "SELECT ID, username FROM tghousandusers ORDER BY username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Options</th>
            </tr>
            <?php
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td><a href='userdetails.php?ID=" . $row['ID'] . "'>VIEW</a>";
                if ($_SESSION['ID'] == $row['ID']) {
                    echo " | <a href='updatepassword.php?ID=" . $row['ID'] . "'>UPDATE PASSWORD</a> | ";
                }
                if ($_SESSION['ID'] == $row['ID']) {
                    echo "<a href='updateprofile.php?ID=" . $row['ID'] . "'>UPDATE PROFILE</a>";
                }
            }
            ?>
        </table>

        <?php
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
include_once "footer.inc.php";
?>
