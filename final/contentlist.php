<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 2:24 PM
 */
$pagename = "Class Content List";
include_once "header.inc.php";

if(!isset($_SESSION['username'])){
    echo "Access denied. Please <a href='login.php'>log in</a> to view content.";
}else {

    echo "<a href='contentcreate.php'>Add new content</a>";
    echo "</br>";

    try {
        $sql = "SELECT ID, classabbr, userID FROM tghousandcontent ORDER BY classabbr";
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
                echo "<td>" . $row['classabbr'] . "</td>";
                echo "<td><a href='contentdetails.php?ID=" . $row['ID'] . "'>VIEW</a>";
                if ($_SESSION['ID'] == $row['userID']) {
                    echo " | <a href='updatecontent.php?ID=" . $row['ID'] . "'>UPDATE</a> | ";
                }
                if ($_SESSION['ID'] == $row['userID']) {
                    echo "<a href='contentdelete.php?ID=" . $row['ID'] . "&C=" . $row['classabbr'] . "''>DELETE</a>";
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
