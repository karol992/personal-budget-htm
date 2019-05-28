<?php

	session_start();
	
	if ((!isset($_POST['email'])) || (!isset($_POST['password']))) {
		header('Location: index.php');
		exit();
	}

	require_once "database.php";
	
	if (isset($_POST['email'])) {	
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password');
	
		if (empty($email) || empty($password)) {
			$_SESSION['login_error'] = '<div class="input_error">Nieprawidłowy email lub hasło!</div>';
			header('Location: index.php');
		} else {
			$userQuery = $db->prepare('SELECT * FROM users WHERE email = :email');
			$userQuery->bindValue(':email', $email, PDO::PARAM_STR);
			$userQuery->execute();
			$user = $userQuery->fetch();
			if ($user && password_verify($password, $user['password'])) {
					$_SESSION['id'] = $user['id'];
					$_SESSION['username'] = $user['username'];
					$_SESSION['email'] = $user['email'];
					unset($_SESSION['login_error']);
					header('Location: menu.php');
				}
		}
	} else {
		header('Location: index.php');
	}
?>