<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 2:45 PM
 */

$pagename = "User Details";
include_once "header.inc.php";

if(!isset($_SESSION['username'])){
    echo "Access denied. Please <a href='login.php'>log in</a> to view content.";
}else {
    try {
        $sql = "SELECT * FROM tghousandusers WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $_GET['ID']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);?>
        <table>
            <tr>
                <td>ID</td>
                <td><?php echo $row['ID']?></td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><?php echo $row['firstname']?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo $row['username']?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo $row['email']?></td>
            </tr>
            <tr>
                <td>Biography</td>
                <td><?php echo $row['bio']?></td>
            </tr>
            <tr>
                <td>Registration Date</td>
                <td><?php
                        $registrationDate = $row['inputdate'];
                        $formattedDate = date("F jS, Y", $registrationDate);
                        echo $formattedDate;?>
                </td>
            </tr>
        </table>
    <?php
    } catch (PDOException $e) {
        die($e->getMessage());
    }

}
include_once "footer.inc.php";
?>