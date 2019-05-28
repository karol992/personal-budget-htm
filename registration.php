<?php

	session_start();
	
	if (isset($_SESSION['error'])) unset($_SESSION['error']);
	
	if (isset($_POST['email'])) {
		//Validation start
		$correct_flag=true;
		
		//Check correctness of nick
		$username = $_POST['username'];
		
		//Check length of nick
		if ((strlen($username)<3) || (strlen($username)>20)) {
			$correct_flag=false;
			$_SESSION['e_username']="Nick musi posiadać od 3 do 20 znaków!";
		}
		
		if (ctype_alnum($username)==false) {
			$correct_flag=false;
			$_SESSION['e_username']="Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
		}
		
		// Check correctness of email 
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$emailB = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		if (($emailB!=$email) || !$email) {
			$correct_flag=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		} else if ($email){
			require_once "database.php";
			$emailQuery = $db->prepare("SELECT email FROM users WHERE email=:email");
			$emailQuery->bindValue(':email', $email, PDO::PARAM_STR);
			$emailQuery->execute();
			if($emailQuery->rowCount()) {
				$correct_flag=false;
				$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!"; 
			}
		}
		
		//Check correctness of password
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		if ((strlen($password1)<8) || (strlen($password1)>20)) {
			$correct_flag=false;
			$_SESSION['e_password']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		if ($password1!=$password2) {
			$correct_flag=false;
			$_SESSION['e_password']="Podane hasła nie są identyczne!";
		}	
		
		$password_hash = password_hash($password1, PASSWORD_DEFAULT);
						
		
		//Save data
		$_SESSION['fr_username'] = $username;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_password1'] = $password1;
		$_SESSION['fr_password2'] = $password2;
		
		//No validation crashes
		if ($correct_flag) {
			require_once "database.php";
			//add new user to users
			$registerUser=$db->prepare("INSERT INTO users VALUES (NULL, :username, :password_hash, :email)");
			$registerUser->bindValue(':username', $username, PDO::PARAM_STR);
			$registerUser->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
			$registerUser->bindValue(':email', $email, PDO::PARAM_STR);
			$registerUser->execute();
			
			//get user id
			$queryNewUserId=$db->prepare("SELECT id FROM users WHERE email=:email");
			$queryNewUserId->bindValue(':email', $email, PDO::PARAM_STR);
			$queryNewUserId->execute();
			$newUserId=$queryNewUserId->fetch();
			//copy default categories
			$copyPayments=$db->prepare("INSERT INTO payment_methods_assigned_to_users (id, user_id, name) SELECT NULL, :newUserId, name FROM payment_methods_default");
			$copyPayments->bindValue(':newUserId',$newUserId['id'],PDO::PARAM_INT);
			$copyPayments->execute();
			$copyIncomes=$db->prepare("INSERT INTO incomes_category_assigned_to_users (id, user_id, name) SELECT NULL, :newUserId, name FROM incomes_category_default");
			$copyIncomes->bindValue(':newUserId',$newUserId['id'],PDO::PARAM_INT);
			$copyIncomes->execute();
			$copyExpenses=$db->prepare("INSERT INTO expenses_category_assigned_to_users (id, user_id, name) SELECT NULL, :newUserId, name FROM expenses_category_default");
			$copyExpenses->bindValue(':newUserId',$newUserId['id'],PDO::PARAM_INT);
			$copyExpenses->execute();
			
			header('Location: index.php');
			exit();
		}
	}
	
	
?>
<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Budżet osobisty</title>
	<meta name="description" content="Budżet osobisty">
	<meta name="keywords" content="dochody, wydatki, bilans">
	<meta name="author" content="Karol Sołek">
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/registration.css">
	
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>

<body>
	
	<header>
			
		<div class="col-sm-12 logo">
			<h1><span class="fa fa-calculator fa-fw"></span>PersonalBudget</h1>
		</div>
	
		<nav class="navbar navbar-light navbar-expand ribbon">
		
		
			<div class="collapse navbar-collapse  justify-content-center" id="mainmenu">
			
				<ul class="navbar-nav offset-6">
					
					<li class="nav-item">
						<a class="nav-link" href="index.php"><span class="fa fa-sign-in fa-fw"></span>Logowanie</a>
					</li>
				
				</ul>
			
			</div>
		
		</nav>
	
	</header>
	
	<main>
	
		<form class="col-12 col-sm-8 col-md-6 col-xl-4 offset-sm-2 offset-md-3 offset-xl-4 form-box" method="post">

			 <div class="input-container">
				<i class="fa fa-user bg-icon fa-fw single-icon"></i>
				<input class="input-field" type="text" placeholder="imię" onfocus="this.placeholder=''" onblur="this.placeholder='imię'"
				value="<?php
				if (isset($_SESSION['fr_username'])) {
					echo $_SESSION['fr_username'];
					unset($_SESSION['fr_username']);
				}
				?>" name="username">
				
			 </div>
			  
			 <div class="input-container">
				<i class="fa fa-envelope bg-icon fa-fw single-icon"></i>
				<input class="input-field" type="email" placeholder="e-mail" onfocus="this.placeholder=''" onblur="this.placeholder='e-mail'" 
				value="<?php
					if (isset($_SESSION['fr_email'])) {
						echo $_SESSION['fr_email'];
						unset($_SESSION['fr_email']);
					}
				?>" name="email">
			 </div>

			 <div class="input-container">
				<i class="fa fa-lock bg-icon fa-fw single-icon"></i>
				<input class="input-field" type="password" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" 
				value="<?php
					if (isset($_SESSION['fr_password1'])) {
						echo $_SESSION['fr_password1'];
						unset($_SESSION['fr_password1']);
					}
				?>" name="password1">
			 </div>
			  
			 <div class="input-container">
				<i class="bg-icon"> <i class="fa fa-lock fa-fw single-icon"></i><i class="fa fa-reply fa-fw single-icon"></i></i>
				<input class="input-field" type="password" placeholder="powtórz hasło" onfocus="this.placeholder=''" onblur="this.placeholder='powtórz hasło'" 
				value="<?php
					if (isset($_SESSION['fr_password2']))
					{
						echo $_SESSION['fr_password2'];
						unset($_SESSION['fr_password2']);
					}
				?>" name="password2">
			 </div>
			
			<div class="submit-container">
				<i class="fa fa-user-plus fa-fw submit-icon"></i>
				<button type="submit" class="btn">Zarejestruj</button>
			</div>
			<?php
				if (isset($_SESSION['e_username'])) {
					echo '<div class="input_error">'.$_SESSION['e_username'].'</div>';
					unset($_SESSION['e_username']);
				}
				if (isset($_SESSION['e_email'])) {
					echo '<div class="input_error">'.$_SESSION['e_email'].'</div>';
					unset($_SESSION['e_email']);
				}
				if (isset($_SESSION['e_password'])) {
					echo '<div class="input_error">'.$_SESSION['e_password'].'</div>';
					unset($_SESSION['e_password']);
				}
				if (isset($_SESSION['error']))	{
					echo $_SESSION['error'];
					unset($_SESSION['error']);
				}
				
			?>
		</form>
	
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
</body>
</html>