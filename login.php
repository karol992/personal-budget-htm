<?php

	session_start();
	
	if ((!isset($_POST['email'])) || (!isset($_POST['password'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
	else
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		$email = htmlentities($email, ENT_QUOTES, "UTF-8");
	
		if ($user_info_request = @$connection->query(
		sprintf("SELECT * FROM users WHERE email='%s'",
		mysqli_real_escape_string($connection,$email))))
		{
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
	
?>