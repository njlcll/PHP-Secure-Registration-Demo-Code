<?php

//if form has been submitted process it
if(isset($_POST['submit'])){

    if (! isset($_POST['username'])) {
    	$error[] = "Please fill out all fields";
    }

    if (! isset($_POST['email'])) {
    	$error[] = "Please fill out all fields";
    }

    if (! isset($_POST['password'])) {
    	$error[] = "Please fill out all fields";
    }

	$username = $_POST['username'];

	//very basic validation
	if (! $user->isValidUsername($username)){
		$error[] = 'Usernames must be at least 3 Alphanumeric characters';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $username));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if (! empty($row['username'])){
			$error[] = 'Username provided is already in use.';
		}
	}

	$user->check_password($_POST, $error);

	//email validation
	$email = htmlspecialchars_decode($_POST['email'], ENT_QUOTES);
	if (! filter_var($email, FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $email));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if (! empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}
	}


	//if no errors have been created carry on
	if (!isset($error)){

		//hash the password
		$hashedpassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members 
			(username,password,email,active) 
				VALUES
			 (:username, :password, :email, :active)');
			$stmt->execute(array(
				':username' => $username,
				':password' => $hashedpassword,
				':email' => $email,
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');

			//send email
			$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "<p>Thank you for registering at demo site.</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activasion'>".DIR."activate.php?x=$id&y=$activasion</a></p>
			<p>Regards Site Admin</p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index page
			header('Location: index.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
	}
}
?>