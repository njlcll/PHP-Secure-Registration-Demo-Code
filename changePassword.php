<?php require('includes/config.php');

if (isset($_POST['submit'])) {
    require_once("includes/changePassword.php");
}

//define page title
$title = 'Change Password';

//include header template
require_once('layout/header.php');
if (isset($_POST['submit'])) {
    if (isset($error)) {
        foreach ($error as $error) {
            echo '<p class="bg-danger">' . $error . '</p>';
        }
    } else {
        echo '<p class="bg-success">Your Password has been changed</p>';
    }
}

?>

<div class="row">

    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        <form role="form" method="post" action="" autocomplete="off">
            <div class="form-group">
                <h2>Change Password </h2>
                <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="1">
            </div>

            <div class="form-group">
                <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="2">
            </div>


            <hr>
            <div class="row">
                <div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Login" class="btn btn-primary btn-block btn-lg" tabindex="3"></div>
            </div>
        </form>
    </div>
</div>


</div>

<?php
//include header template
require('layout/footer.php');
?>