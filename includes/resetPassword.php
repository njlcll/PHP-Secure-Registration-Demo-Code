<?php
require_once("classes/user.php");
//if logged in redirect to members page
if ($user->is_logged_in() ){ 
	header('Location: memberpage.php'); 
	exit(); 
}

$resetToken = $_GET['key']??"";

$stmt = $db->prepare('SELECT resetToken, resetComplete FROM members WHERE resetToken = :token');
$stmt->execute(array(':token' => $resetToken));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//if no token from db then kill the page
if (empty($row['resetToken'])){
	$stop = 'Invalid token provided, please use the link provided in the reset email.';
} elseif($row['resetComplete'] == 'Yes') {
	$stop = 'Your password has already been changed!';
}

//if form has been submitted process it
if (isset($_POST['submit'])){

	if (! isset($_POST['password']) || ! isset($_POST['passwordConfirm'])) {
		$error[] = 'Both Password fields are required to be entered';
	}

    $user->check_password($_POST, $error);

	//if no errors have been created carry on
	if (! isset($error)){

		//hash the password
		$hashedpassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

		try {

			$stmt = $db->prepare("UPDATE members SET password = :hashedpassword, resetComplete = 'Yes'  WHERE resetToken = :token");
			$stmt->execute(array(
				':hashedpassword' => $hashedpassword,
				':token' => $row['resetToken']
			));

			//redirect to index page
			header('Location: login.php?action=resetAccount');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
	}
}
?>