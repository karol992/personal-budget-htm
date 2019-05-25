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
		$password = htmlentities($password, ENT_QUOTES, "UTF-8");
	
		if ($result = @$connection->query(
		sprintf("SELECT * FROM users WHERE email='%s' AND password='%s'",
		mysqli_real_escape_string($connection,$email),
		mysqli_real_escape_string($connection,$password))))
		{
			$number_of_users = $result->num_rows;
			if($number_of_users>0)
			{
				$_SESSION['logged'] = true;
				
				$wiersz = $result->fetch_assoc();
				/*$_SESSION['id'] = $wiersz['id'];
				$_SESSION['user'] = $wiersz['user'];
				$_SESSION['drewno'] = $wiersz['drewno'];
				$_SESSION['kamien'] = $wiersz['kamien'];
				$_SESSION['zboze'] = $wiersz['zboze'];
				$_SESSION['email'] = $wiersz['email'];
				$_SESSION['dnipremium'] = $wiersz['dnipremium'];*/
				
				unset($_SESSION['error']);
				$result->free_result();
				header('Location: menu.php');
				
			} else {
				
				$_SESSION['error'] = '<span style="color:red">Nieprawidłowy email lub hasło!</span>';
				header('Location: index.php');
				
			}
			
		}
		
		$connection->close();
	}
	
?>