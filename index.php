<?php

	session_start();
	
	//Remove temporary input text
	if (isset($_SESSION['fr_username'])) unset($_SESSION['fr_username']);
	if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
	if (isset($_SESSION['fr_password1'])) unset($_SESSION['fr_password1']);
	if (isset($_SESSION['fr_password2'])) unset($_SESSION['fr_password2']);
	
	//Remove registration errors
	if (isset($_SESSION['e_username'])) unset($_SESSION['e_username']);
	if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
	if (isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
	
	if (isset($_SESSION['id'])) {
		header('Location: menu.php');
		exit();
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
	<link rel="stylesheet" href="css/login.css">
	
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
						<a class="nav-link" href="registration.php"><span class="fa fa-user-plus fa-fw"></span>Rejestracja</a>
					</li>
				
				</ul>
			
			</div>
		
		</nav>
	
	</header>
	
	<main>
	
		<form class="col-12 col-sm-8 col-md-6 col-xl-4 offset-sm-2 offset-md-3 offset-xl-4 form-box" action="login.php" method="post">

			<div class="input-container">
				<i class="fa fa-envelope bg-icon fa-fw single-icon"></i>
				<input class="input-field" type="email" name="email" placeholder="e-mail" onfocus="this.placeholder=''" onblur="this.placeholder='e-mail'">
			</div>

			<div class="input-container">
				<i class="fa fa-lock bg-icon fa-fw single-icon"></i>
				<input class="input-field" type="password" name="password" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'">
			</div>

			<div class="submit-container">
				<i class="fa fa-sign-in fa-fw submit-icon"></i>
				<button type="submit" class="btn">Zaloguj</button>
			</div>
				<?php 
					if (isset($_SESSION['login_error'])) {
						echo $_SESSION['login_error']; unset($_SESSION['login_error']);
					}
					if (isset($_SESSION['registration_done'])) {
						echo "<div>Rejestracja powiodła się! Możesz się zalogować.</div>";
						unset($_SESSION['registration_done']);
					}
				?>
		</form>
		
	
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
</body>
</html>