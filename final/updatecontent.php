<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 3:29 PM
 */
$pagename = "Update Profile";
include_once "header.inc.php";

//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errmsg = 0;
$errabbr = "";
$errlong = "";
$errdescription = "";
//continue with necessary fields

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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        /* ***********************************************************************
         * SANITIZE USER DATA
         * Use strtolower()  for emails, usernames and other case-sensitive info
         * Use trim() for ALL user-typed data -- even those not required
         * CAUTION:  Radio buttons are a bit different.
         *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
         * ***********************************************************************
         */
        $formdata['classabbr'] = strtoupper(trim($_POST['classabbr']));
        $formdata['classlong'] = trim($_POST['classlong']);
        $formdata['description'] = str_replace("&nbsp;", "", strip_tags($_POST['description']));

        /* ***********************************************************************
         * CHECK EMPTY FIELDS
         * Check for empty data for every required field
         * Do not do for things like apartment number, middle initial, etc.
         * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
         *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
         * ***********************************************************************
         */
        if (empty($formdata['classabbr'])) {
            $errabbr = "The abbreviation for the class name is required.";
            $errmsg = 1;
        }
        if (empty($formdata['classlong'])) {
            $errlong = "The full class name is required.";
            $errmsg = 1;
        }
        if (empty($formdata['description'])) {
            $errdescription = "The class description is required.";
            $errmsg = 1;
        }

        /* ***********************************************************************
         * CHECK MATCHING FIELDS
         * Check to see if important fields match
         * Usually used for passwords and sometimes emails.  We'll do passwords.
         * ***********************************************************************
         */
        //nothing to put here

        /* ***********************************************************************
         * CHECK EXISTING DATA
         * Check data to avoid duplicates
         * Usually used with emails and usernames - We'll do usernames
         * ***********************************************************************
         */
        if($formdata['classabbr'] != $_POST['classabbr']) {
            Try {
                $sql = "SELECT * FROM tghousandcontent WHERE classabbr = :classabbr";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':classabbr', $formdata['classabbr']);
                $stmt->execute();
                $countclass = $stmt->rowCount();
                if ($countclass > 0) {
                    $errmsg = 1;
                    $errabbr = "Class abbreviation already in use.";
                }
            } catch (PDOException $e) {
                echo "<p class='error'>Error checking duplicate users!" . $e->getMessage() . "</p>";
                exit();
            }
        }


        /* ***********************************************************************
         * CONTROL STATEMENT TO HANDLE ERRORS
         * ***********************************************************************
         */
        if ($errmsg == 1) {
            echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
        } else {

            /* ***********************************************************************
             * HASH SENSITIVE DATA
             * Used for passwords and other sensitive data
             * If checked for matching fields, do NOT hash and insert both to the DB
             * ***********************************************************************
             */
            //nothing to put here

            /* ***********************************************************************
             * INSERT INTO THE DATABASE
             * NOT ALL data comes from the form - Watch for this!
             *    For example, input dates are not entered from the form
             * ***********************************************************************
             */

            try {
                $sql = "UPDATE tghousandcontent 
                        SET classabbr = :classabbr, description = :description, classlong = :classlong
                        WHERE ID = :ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':classabbr', $formdata['classabbr']);
                $stmt->bindValue(':classlong', $formdata['classlong']);
                $stmt->bindValue(':description', $formdata['description']);
                $stmt->bindValue(':ID', $id);
                $stmt->execute();

                //hide the form
                $showform = 0;
                echo "<p>Class content updated.</p>";
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        } // else errormsg
    }//submit

//display form if Show Form Flag is true
    if ($showform == 1) {
        $sql = "SELECT * FROM tghousandcontent WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $id);
        $stmt->execute();
        $row = $stmt->fetch();
        ?>
        <form name="updatecontent" id="updatecontent" method="post" action="updatecontent.php">
            <table>
                <tr><th><label for="classabbr">Abbreviated Class Name:</label></th>
                    <td><input name="classabbr" id="classabbr" type="text" size="20" placeholder="Required Abbreviation"
                               value="<?php if(isset($formdata['classabbr'])){echo $formdata['classabbr'];}else{echo $row['classabbr'];}?>"/>
                        <span class="error">*<?php if(isset($errabbr)){echo $errabbr;}?></span></td>
                </tr>
                <tr><th><label for="classlong">Full Class Name:</label></th>
                    <td><input type="text" name="classlong" id="classlong" size="30" placeholder="Required Full Name"
                               value="<?php if(isset($formdata['classlong'])){echo $formdata['classlong'];}else{echo $row['classlong'];}?>"/>
                        <span class="error">*<?php if(isset($errlong)){echo $errlong;}?></span></td>
                </tr>
                <tr>
                    <th><label for="description">Description: </label></th>
                    <td><textarea name="description" id="description">
                        <?php if(isset($formdata['description']) && !empty($formdata['description'])){
                            echo $formdata['description'];
                        }else{
                            echo $row['description'];}?></textarea>
                        <span class="error">* <?php if(isset($errdescription)){echo $errdescription;}?></span></td>
                </tr>
                <tr>
                    <th><label for="submit">Submit</label></th>
                    <td><input type="hidden" id="ID" name="ID" value="<?php echo $row['ID']; ?>"/>
                        <input type="submit" name="submit" id="submit" value="submit"</td>
                </tr>
            </table>
        </form>
        <?php
    }//end showform
}
include_once "footer.inc.php";
?>








