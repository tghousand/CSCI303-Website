<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 3:08 PM
 */
$pagename = "Update Password";
include_once "header.inc.php";

//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errmsg = 0;
$errpwd = "";
$errpwd2 = "";
//continue with necessary fields

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ID'])){
    $id = $_GET['ID'];
}elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ID'])){
    $id = $_POST['ID'];
}

if($_SESSION['ID'] != $id){
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
        $formdata['password'] = trim($_POST['password']);
        $formdata['passwordCheck'] = trim($_POST['passwordCheck']);

        /* ***********************************************************************
         * CHECK EMPTY FIELDS
         * Check for empty data for every required field
         * Do not do for things like apartment number, middle initial, etc.
         * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
         *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
         * ***********************************************************************
         */
        if (empty($formdata['password'])) {$errpwd = "The password is required."; $errmsg = 1; }
        if (empty($formdata['passwordCheck'])) {$errpwd2 = "The confirmation password is required."; $errmsg = 1; }

        /* ***********************************************************************
         * CHECK MATCHING FIELDS
         * Check to see if important fields match
         * Usually used for passwords and sometimes emails.  We'll do passwords.
         * ***********************************************************************
         */
        if($formdata['password'] != $formdata['passwordCheck'])
        {
            $errmsg = 1;
            $errpwd2 = "The passwords do not match.";
        }

        /* ***********************************************************************
         * CHECK EXISTING DATA
         * Check data to avoid duplicates
         * Usually used with emails and usernames - We'll do usernames
         * ***********************************************************************
         */


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
            $hashedpassword = password_hash($formdata['password'], PASSWORD_BCRYPT);

            /* ***********************************************************************
             * INSERT INTO THE DATABASE
             * NOT ALL data comes from the form - Watch for this!
             *    For example, input dates are not entered from the form
             * ***********************************************************************
             */

            try {
                $sql = "UPDATE tghousandusers SET password = :password WHERE ID = :ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':password', $hashedpassword);
                $stmt->bindValue(':ID', $id);
                $stmt->execute();

                //hide the form
                $showform = 0;
                echo "<p>Password Updated</p>";
                header("Location: logout.php");
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        } // else errormsg
    }//submit

//display form if Show Form Flag is true
    if ($showform == 1) {
        $sql = "SELECT * FROM tghousandusers WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $id);
        $stmt->execute();
        $row = $stmt->fetch();
        echo "Upon password change, you will be logged out and will be required to log back in under your new password.";
        ?>
        <form name="updatepassword" id="updatepassword" method="post" action="updatepassword.php">
            <table>
                <tr><th><label for="password">Password:</label></th>
                    <td><input type="password" name="password" id="password" size="45" placeholder="Required Password"
                               value="<?php if(isset($formdata['password'])){echo $formdata['password'];}?>"/>
                        <span class="error">*<?php if(isset($errpwd)){echo $errpwd;}?></span></td>
                </tr>
                <tr><th><label for="pwd2">Confirm Password:</label></th>
                    <td><input type="password" name="passwordCheck" id="passwordCheck" size="45" placeholder="Required Password Again"/>
                        <span class="error">*<?php if(isset($errpwd2)){echo $errpwd2;}?></span></td>
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
