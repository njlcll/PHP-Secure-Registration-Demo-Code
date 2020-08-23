<?php

class User
{
	private $_db;

	function __construct($db)
	{
		$this->_db = $db;
	}

	private function get_user_hash($username)
	{
		try {
			$stmt = $this->_db->prepare('SELECT password, username, memberID FROM members WHERE username = :username AND active="Yes" ');
			$stmt->execute(array('username' => $username));

			return $stmt->fetch();
		} catch (PDOException $e) {
			echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
		}
	}

	public function isValidUsername($username)
	{
		if (strlen($username) < 3) {
			return false;
		}

		if (strlen($username) > 17) {
			return false;
		}

		if (!ctype_alnum($username)) {
			return false;
		}

		return true;
	}

	public function login($username, $password)
	{
		if (!$this->isValidUsername($username)) {
			return false;
		}

		if (strlen($password) < MIN_PASSWORD_LENGTH) {
			return false;
		}

		$row = $this->get_user_hash($username);

		$row['password'] = $row['password'] ?? "";
		if (password_verify($password, $row['password'])) {

			$_SESSION['loggedin'] = true;
			$_SESSION['username'] = $row['username'];
			$_SESSION['memberID'] = $row['memberID'];

			return true;
		}
	}

	public function logout()
	{
		session_destroy();
	}

	public function is_logged_in()
	{
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
			return true;
		}
	}

	public   function check_password($post, &$error)
	{

		//basic validation
		if (strlen($post['password']) < MIN_PASSWORD_LENGTH) {
			$error[] = 'Password is too short.';
		}

		if (strlen($post['passwordConfirm']) < MIN_PASSWORD_LENGTH) {
			$error[] = 'Confirm password is too short.';
		}

		if ($post['password'] != $post['passwordConfirm']) {
			$error[] = 'Passwords do not match.';
		}
	}
}

function get_user($db)
{
	$user = new User($db);
	return $user;
}
