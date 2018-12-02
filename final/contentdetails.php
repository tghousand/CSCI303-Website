<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 2:45 PM
 */

$pagename = "User Details";
include_once "header.inc.php";

    try {
        $sql = "SELECT * FROM tghousandcontent WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $_GET['ID']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $sql2 = "SELECT username FROM tghousandusers WHERE ID = :ID";
        $stmt = $pdo->prepare($sql2);
        $stmt->bindValue(':ID', $row['userID']);
        $stmt->execute();
        $row2 = $stmt->fetch(PDO::FETCH_ASSOC);?>
        <table>
            <tr>
                <td>ID</td>
                <td><?php echo $row['ID']?></td>
            </tr>
            <tr>
                <td>Abbreviated Class Name</td>
                <td><?php echo $row['classabbr']?></td>
            </tr>
            <tr>
                <td>Full Class Name</td>
                <td><?php echo $row['classlong']?></td>
            </tr>
            <tr>
                <td>Date Posted</td>
                <td><?php
                    $registrationDate = $row['inputdate'];
                    $formattedDate = date("F jS, Y", $registrationDate);
                    echo $formattedDate;?>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo $row['description']?></td>
            </tr>
            <tr>
                <td>Author</td>
                <td><?php
                        echo $row2['username'] . " (" . $row['userID'] . ")";?>
                </td>
            </tr>
        </table>
    <?php
    } catch (PDOException $e) {
        die($e->getMessage());

}
include_once "footer.inc.php";
?>