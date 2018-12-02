<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 12:40 PM
 */
$pagename = "Home";
require_once "header.inc.php";
?>
<div>
<div class = "leftside">
    <table>
        <tr><th>Classes</th></tr>
        <?php
        try{
        $sql = "SELECT ID, classabbr FROM tghousandcontent ORDER BY classabbr";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row){
            echo "<tr>";
            echo "<td><a href='contentdetails.php?ID=" . $row['ID'] . "'>". $row['classabbr'] ."</a></td>";
            echo "</tr>";
        }}
        catch (PDOException $e){
            die($e->getMessage());
        }
        ?>
    </table>
</div>

<div class="rightside">
    <p>This is my final project for CSCI303. This is website deals with managing simple logins and database interactions.</p>
    <p>The first database in use for this website is a database of the users. In that database are the IDs, usernames, passwords, emails, user creation dates, first names, and short biographies of what the user puts in.
    This allows for parts items to be updated other than just a username or password. There are no current administrator logins or administrative functionality. However, one user profile is not able to alter another user profile.
    User profiles are able to only edit content that they put into the second database.</p>
    <p>The second database is one that holds extra content under the guise of classes at university. This database holds IDs, abbreviated class names, full length class names, class descriptions, creation dates, and the IDs of the users that create the entries.
    This allows for cross reference between the two tables that allows only the user profile that created the class entry to edit or delete it. There is also a search function
    on the website. The search page will search the entries in the second database to find matches for the search term. This searches the abbreviation, the full name, and the description.</p>
    <p>This website was programmed by Tyler Housand using skills learned through CSCI303 Intro to Server-Side Web Application Development using PHP and HTML.</p>
</div>
</div>

<?php
require_once "footer.inc.php"; ?>
