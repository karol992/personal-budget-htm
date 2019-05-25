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
	
		if ($result = @$connection->query(
		sprintf("SELECT * FROM users WHERE email='%s'",
		mysqli_real_escape_string($connection,$email))))
		{
			$number_of_users = $result->num_rows;
			if($number_of_users>0)
			{
				$line = $result->fetch_assoc();
				
				if (password_verify($password, $line['password']))
				{
					$_SESSION['logged'] = true;
					$_SESSION['id'] = $line['id'];
					$_SESSION['username'] = $line['username'];
					$_SESSION['email'] = $line['email'];
					
					unset($_SESSION['error']);
					$result->free_result();
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