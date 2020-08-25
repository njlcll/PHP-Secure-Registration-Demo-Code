<?php

//if form has been submitted process it
if(isset($_POST['submit'])){

    if( !$user->is_logged_in() ){ header('Location: index.php'); exit(); }

   

    $user->check_password($_POST, $error);

    
	if (!isset($error)){

		//hash the password
		$hashedpassword = password_hash($_POST['password'], PASSWORD_BCRYPT);		
		try {

            $stmt = $db->prepare("UPDATE members SET password = :hashedpassword
              WHERE memberID = :memberID");
				$stmt->execute(array(
                    ':hashedpassword' => $hashedpassword,
                    ':memberID' => $_SESSION['memberID']
                ));

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
	}
}
?>