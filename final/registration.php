<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 12:57 PM
 */
$pagename = "User Registration";
include_once "header.inc.php";

//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errmsg = 0;
$errfname = "";
$errlname = "";
$erruname = "";
$erremail = "";
$errpwd = "";
$errpwd2 = "";
$errgender = "";
$errclassyr = "";
$errbio = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
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
    $formdata['password'] = trim($_POST['password']);
    $formdata['passwordCheck'] = trim($_POST['passwordCheck']);
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
    if (empty($formdata['password'])) {$errpwd = "The password is required."; $errmsg = 1; }
    if (empty($formdata['passwordCheck'])) {$errpwd2 = "The confirmation password is required."; $errmsg = 1; }
    if (empty($formdata['bio'])) {$errbio = "The biography is required."; $errmsg = 1; }
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
    try
{
    $sql = "SELECT * FROM tghousandusers WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $formdata['username']);
    $stmt->execute();
    $countusername = $stmt->rowCount();
    if ($countusername > 0)
    {
        $errmsg = 1;
        $erruname = "Username already taken.";
    }
}
catch (PDOException $e)
{
    echo "<p class='error'>Error checking duplicate users!" . $e->getMessage() . "</p>";
    exit();
}

    /* ***********************************************************************
     * CONTROL STATEMENT TO HANDLE ERRORS
     * ***********************************************************************
     */
    if($errmsg == 1)
    {
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else{

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

        try{
            $sql = "INSERT INTO tghousandusers (username, password, email, firstname, bio, inputdate) 
                    VALUES (:username, :password, :email, :firstname, :bio, :inputdate) ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':username', $formdata['username']);
            $stmt->bindValue(':email', $formdata['email']);
            $stmt->bindValue(':password', $hashedpassword);
            $stmt->bindValue(':firstname', $formdata['firstname']);
            $stmt->bindValue(':bio', $formdata['bio']);
            $stmt->bindValue(':inputdate', $rightnow);
            $stmt->execute();

            $showform =0; //hide the form
            echo "<p class='success'>Thanks for entering your information.</p>";
        }
        catch (PDOException $e)
        {
            die( $e->getMessage() );
        }
    } // else errormsg
}//submit

//display form if Show Form Flag is true
if($showform == 1)
{
    ?>
    <form name="adduser" id="adduser" method="post" action="registration.php">
        <table>
            <tr><th><label for="firstname">First Name:</label></th>
                <td><input name="firstname" id="firstname" type="text" size="20" placeholder="Required First Name"
                           value="<?php if(isset($formdata['firstname'])){echo $formdata['firstname'];}?>"/>
                    <span class="error">*<?php if(isset($errfname)){echo $errfname;}?></span></td>
            </tr>
            <tr><th><label for="username">Username:</label></th>
                <td><input type="text" name="username" id="username" size="30" placeholder="Required Username"
                           value="<?php if(isset($formdata['username'])){echo $formdata['username'];}?>"/>
                    <span class="error">*<?php if(isset($erruname)){echo $erruname;}?></span></td>
            </tr>
            <tr><th><label for="email">Email:</label></th>
                <td><input type="email" name="email" id="email" size="50" placeholder="Required Email"
                           value="<?php if(isset($formdata['email'])){echo $formdata['email'];}?>"/>
                    <span class="error">*<?php if(isset($erremail)){echo $erremail;}?></span></td>
            </tr>
            <tr><th><label for="password">Password:</label></th>
                <td><input type="password" name="password" id="password" size="45" placeholder="Required Password"
                           value="<?php if(isset($formdata['password'])){echo $formdata['password'];}?>"/>
                    <span class="error">*<?php if(isset($errpwd)){echo $errpwd;}?></span></td>
            </tr>
            <tr><th><label for="pwd2">Confirm Password:</label></th>
                <td><input type="password" name="passwordCheck" id="passwordCheck" size="45" placeholder="Required Password Again"/>
                    <span class="error">*<?php if(isset($errpwd2)){echo $errpwd2;}?></span></td>
            </tr>
            <tr><th><label for="bio">Biography:</label></th>
                <td><span class="error">* <?php if(isset($errbio)){echo $errbio;}?></span>
                    <textarea name="bio" id="bio" placeholder="Required Biography"><?php if(isset($formdata['bio'])){echo $formdata['bio'];}?></textarea>
                </td>
            </tr>
            <tr><th><label for="submit">Submit:</label></th>
                <td><input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>

        </table>
    </form>
    <?php
}//end showform
include_once "footer.inc.php";
?>
