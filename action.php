<?php

function request()
{
	// Login
	if (isset($_POST['login'])) {
		if (getUser())
			return;

		// Check input
		if (!isset($_POST['email']) || !isset($_POST['password']))
			die('Email and Password are required.');
		$email = $_POST['email'];
		$password = $_POST['password'];

		// Registration
		if (!isUser($email))
			addUser($email, $password);

		// Login
		$user = checkPassword($email, $password);
		if (!$user)
			die('Password does not match Email.');
		else {
			$hash = md5(md5($user) . $password);
			setcookie('user', $user, strtotime('+1 month'));
			$_COOKIE['user'] = $user;
			setcookie('password', $hash, strtotime('+1 month'));
			$_COOKIE['password'] = $hash;
		}
	}

	// Logout
	else if (isset($_POST['logout'])) {
		setcookie('user', '', strtotime('-1 day'));
		unset($_COOKIE['user']);
		setcookie('password', '', strtotime('-1 day'));
		unset($_COOKIE['password']);
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	request();
}
