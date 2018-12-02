<?php
/**
 * Created by PhpStorm.
 * User: Tyler Housand
 * Date: 5/2/2018
 * Time: 12:41 PM
 */

$pagename = "Login";  //pagename var is used in the header
require_once "header.inc.php";

//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errormsg = 0;
$errorusername = "";
$errorpassword = "";

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
    $formdata['username'] = trim(strtolower($_POST['username']));
    $formdata['password'] = trim($_POST['password']);

    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every required field
     * Do not do for things like apartment number, middle initial, etc.
     * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * ***********************************************************************
     */
    if (empty($formdata['username'])) {
        $errorusername = "The username is required.";
        $errormsg = 1;
    }
    if (empty($formdata['password'])) {
        $errorpassword = "The password is required.";
        $errormsg = 1;
    }
    /* ***********************************************************************
     * CONTROL STATEMENT TO HANDLE ERRORS
     * ***********************************************************************
     */
    if($errormsg == 1)
    {
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else{
        /* ***********************************************************************
         * CHECK USER AND VERIFY PASSWORD
         * First, check to see if the username submitted exists
         *   - if no, notify user
         *   - if yes, verify password and redirect to confirmation page upon success.
         * ***********************************************************************
         */
        try
        {
            $sqlusers = "SELECT * FROM tghousandusers WHERE username = :username";
            $stmtusers = $pdo->prepare($sqlusers);
            $stmtusers->bindValue(':username', $formdata['username']);
            $stmtusers->execute();
            $row = $stmtusers->fetch();
            $countusers = $stmtusers->rowCount();
            if ($countusers < 1)
            {
                echo  "<p class='error'>This user cannot be found.</p>";
            }
            else
            {
                if (password_verify($formdata['password'], $row['password'])) {
                    $_SESSION['ID'] = $row['ID'];
                    $_SESSION['username'] = $row['username'];
                    $showform = 0;
                    header("Location: confirm.php?state=2");
                }
                else
                {
                    echo "<p class='error'>The username and password combination you entered is not correct.  Please try again.</p>";
                }
            }//if countusers

        }//try
        catch (PDOException $e)
        {
            echo "<div class='error'><p></p>ERROR selecting users!" .$e->getMessage() . "</p></div>";
            exit();
        }
    } // else errormsg
}//submit
if($showform == 1){
    ?>
    <form name="login" id="login" method="POST" action="login.php">

    <table>
        <tr><th><label for="username">Username: </label></th>
            <td><input name="username" id="username" type="text" placeholder="Required Username"
                       value="<?php if(isset($formdata['username']))
                       {echo $formdata['username'];
                       }?>" /><span class="error">* <?php if(isset($errorusername)){echo $errorusername;}?></span></td>
        </tr>
        <tr><th><label for="password">Password: </label></th>
            <td><input name="password" id="password" type="password" placeholder="Required Password"
                       value="<?php if(isset($formdata['password']))
                       {echo $formdata['password'];
                       }?>" /><span class="error">*</span></td>
        </tr>
        <tr><th><label for="submit">Submit: </label></th>
            <td><input type="submit" name="submit" id="submit" value="submit"/></td>
        </tr>
    </table>

    <?php
}//end showform
require_once "footer.inc.php";
?>