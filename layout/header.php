<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title??"Jobsite" ?></title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>
<div class="container ">
<div class="row">

<div class="col-12  ">
    <div class='text-right '>
        <?php
        if(!$user->is_logged_in()) {
        ?>
        <button type="button" class="btn"><a href="login.php">Login</a></button>
        <button type="button" class="btn"><a href="registration.php">Register</a></button>
        <?php
        }else{
            ?>
            <button type="button" class="btn"><a href="logout.php">Log out</a></button>
            <button type="button" class="btn"><a href="changePassword.php">Change Password</a></button>
            <?php
        }

        ?>
    </div>

</div>
<div class="col text-center ">
    <h4>Registration and Login Demo Codes</h4>
</div>

</div>