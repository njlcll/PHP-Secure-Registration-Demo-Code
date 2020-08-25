<?php
//include config
require_once('includes/config.php');

if ($user->is_logged_in() ){ 
	header('Location: memberpage.php'); 
	exit(); 
}

$title = 'PHP Registration and Login Demo :-Home';

//include header template
require_once('layout/header.php');

?>

<?php
//include header template
require('layout/footer.php');
