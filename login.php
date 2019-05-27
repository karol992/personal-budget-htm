<?php

	session_start();
	
	if ((!isset($_POST['email'])) || (!isset($_POST['password']))) {
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	
	try {
		$connection = new mysqli($host, $db_user, $db_password, $db_name);
		if ($connection->connect_errno!=0) {
			throw new Exception(mysqli_connect_errno());
		} else {
			$email = $_POST['email'];
			$password = $_POST['password'];
			
			$email = htmlentities($email, ENT_QUOTES, "UTF-8");
			
			$user_info_request = $connection->query(sprintf("SELECT * FROM users WHERE email='%s'",
			mysqli_real_escape_string($connection,$email)));
			if (!$user_info_request) {
				throw new Exception($connection->error);
			} else {
				$number_of_users = $user_info_request->num_rows;
				if($number_of_users>0)
				{
					$user_info_container = $user_info_request->fetch_assoc();
					
					if (password_verify($password, $user_info_container['password']))
					{
						$_SESSION['logged'] = true;
						$_SESSION['id'] = $user_info_container['id'];
						$_SESSION['username'] = $user_info_container['username'];
						$_SESSION['email'] = $user_info_container['email'];
						
						unset($_SESSION['error']);
						$user_info_request->free_result();
						header('Location: menu.php');
					}
					else 
					{
						$_SESSION['error'] = '<div class="input_error">Nieprawidłowy login lub hasło!</div>';
						header('Location: index.php');
					}
				} else {
					
					$_SESSION['error'] = '<div class="input_error">Nieprawidłowy email lub hasło!</div>';
					header('Location: index.php');
				}
			}
			$connection->close();
		}
	} catch(Exception $e) {
			$_SESSION['error'] = '<div class="input_error">Błąd serwera! Przepraszamy za niedogodności i prosimy o logowanie w innym terminie!</div>
			<div>Informacja developerska: '.$e.'</div>';
			header('Location: index.php');
	}
?>