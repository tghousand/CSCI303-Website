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
$errfname = "";
$erruname = "";
$erremail = "";
$errbio = "";
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
        $formdata['firstname'] = trim($_POST['firstname']);
        $formdata['username'] = trim(strtolower($_POST['username']));
        $formdata['email'] = trim(strtolower($_POST['email']));
        $formdata['bio'] = str_replace("&nbsp;", "", strip_tags($_POST['bio']));

        /* ***********************************************************************
         * CHECK EMPTY FIELDS
         * Check for empty data for every required field
         * Do not do for things like apartment number, middle initial, etc.
         * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
         *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
         * ***********************************************************************
         */
        if (empty($formdata['firstname'])) {
            $errfname = "The first name is required.";
            $errmsg = 1;
        }
        if (empty($formdata['username'])) {$erruname = "The username is required."; $errmsg = 1; }
        if (empty($formdata['email'])) {$erremail = "The email is required."; $errmsg = 1; }
        if (empty($formdata['bio'])) {$errbio = "The biography is required."; $errmsg = 1; }

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
        if($formdata['username'] != $_POST['username']) {
            try {
                $sql = "SELECT * FROM tghousandusers WHERE username = :username";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':username', $formdata['username']);
                $stmt->execute();
                $countusername = $stmt->rowCount();
                if ($countusername > 0) {
                    $errmsg = 1;
                    $erruname = "Username already taken.";
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
                $sql = "UPDATE tghousandusers SET username = :username, firstname = :firstname, email = :email, bio = :bio WHERE ID = :ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':username', $formdata['username']);
                $stmt->bindValue(':firstname', $formdata['firstname']);
                $stmt->bindValue(':email', $formdata['email']);
                $stmt->bindValue(':bio', $formdata['bio']);
                $stmt->bindValue(':ID', $id);
                $stmt->execute();

                //hide the form
                $showform = 0;
                echo "<p>User profile updated.</p>";
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
        ?>
        <form name="updateprofile" id="updateprofile" method="post" action="updateprofile.php">
            <table>
                <tr><th><label for="firstname">First Name:</label></th>
                    <td><input name="firstname" id="firstname" type="text" size="20" placeholder="Required First Name"
                               value="<?php if(isset($formdata['firstname'])){echo $formdata['firstname'];}else{echo $row['firstname'];}?>"/>
                        <span class="error">*<?php if(isset($errfname)){echo $errfname;}?></span></td>
                </tr>
                <tr><th><label for="username">Username:</label></th>
                    <td><input name="username" id="username" type="text" size="20" placeholder="Required Username"
                               value="<?php if(isset($formdata['username'])){echo $formdata['username'];}else{echo $row['username'];}?>"/>
                        <span class="error">*<?php if(isset($erruname)){echo $erruname;}?></span></td>
                </tr><tr><th><label for="email">Email:</label></th>
                    <td><input name="email" id="email" type="email" placeholder="Required Email"
                               value="<?php if(isset($formdata['email'])){echo $formdata['email'];}else{echo $row['email'];}?>"/>
                        <span class="error">*<?php if(isset($erremail)){echo $erremail;}?></span></td>
                </tr>
                <tr>
                    <th><label for="bio">Biography: </label></th>
                    <td><textarea name="bio" id="bio">
                        <?php if(isset($formdata['bio']) && !empty($formdata['bio'])){
                            echo $formdata['bio'];
                        }else{
                            echo $row['bio'];}?></textarea>
                        <span class="error">* <?php if(isset($errbio)){echo $errbio;}?></span></td>
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








