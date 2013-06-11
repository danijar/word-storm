<?php

if($_SERVER['REQUEST_METHOD']=='POST'){

	// login
	if(isset($_POST['login'])){
		if(getUser()) return;

		// check input
		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
		$password = isset($_POST['password']) ? trim($_POST['password']) : '';
		if($email==''||$password=='') die('Email and Password are required.');

		// registration
		if(!isUser($email)) addUser($email, $password);

		// login
		$user = checkPassword($email, $password);
		if(!$user) die('Password doesnt match Email.');
		else {
			$hash = md5(md5($user).$password);
			setcookie('user', $user, strtotime('+1 month')); $_COOKIE['user'] = $user;
			setcookie('password', $hash, strtotime('+1 month')); $_COOKIE['password'] = $hash;
		}
	}

	// logout
	else if(isset($_POST['logout'])){
		setcookie('user', '', strtotime('-1 day')); unset($_COOKIE['user']);
		setcookie('password', '', strtotime('-1 day')); unset($_COOKIE['password']);
	}
}