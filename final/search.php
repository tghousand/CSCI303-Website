<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 3/27/2018
 * Time: 11:48 AM
 */
$pagename = "Search";
include_once "header.inc.php";


if(!isset($_SESSION['username'])){
    echo "Access denied. Please <a href='login.php'>log in</a> to view content.";
}else {
    $showform = 1;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<p>Searching for: " . $_POST['SearchTerm'] . "</p>";
        echo "<hr />";
        $formdata['SearchTerm'] = $_POST['SearchTerm'];

        try {
            $sql = "SELECT ID, classabbr FROM tghousandcontent 
                WHERE classabbr LIKE '%{$formdata['SearchTerm']}%' OR classlong LIKE '%{$formdata['SearchTerm']}%' OR
                      description LIKE '%{$formdata['SearchTerm']}%'   
                ORDER BY classabbr";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();

            if ($count > 0) {
                foreach ($result as $row) {
                    echo "<a href='contentdetails.php?ID=" . $row['ID'] . "'>VIEW</a>: ";
                    echo $row['ID'] . " - " . $row['classabbr'] . "<br />\n";
                }
                $showform = 0;
            } else {
                echo "No results found.";
            }


        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    if ($showform == 1) {
        ?>

        <form name="searchstuff" id="searchstuff" method="post" action="search.php">
            <label for="SearchTerm">Search Categories:</label>
            <input name="SearchTerm" id="SearchTerm" type="text"/>
            <input type="submit" name="submit" id="submit" value="submit"/>
        </form>
        <?php
    }
}

include_once "footer.inc.php";
?>
