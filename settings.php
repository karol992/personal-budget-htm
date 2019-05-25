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
	<link rel="stylesheet" href="css/settings.css">
	
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>

<body>
	
	<header>
		<!-- Logo -->
		<div class="col-sm-12 logo">
			<h1><span class="fa fa-calculator fa-fw"></span>PersonalBudget</h1>
		</div>
		<!-- Navigation Menu -->
		<nav class="navbar navbar-light navbar-expand-lg ribbon">
			<!-- Navigation Switch -->
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<div><span class="navbar-toggler-icon"></span> MENU</div>
			</button>
			<!-- Menu Icons -->
			<div class="collapse navbar-collapse  justify-content-center" id="mainmenu">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="menu.php"><span class="fa fa-home fa-fw"></span>Start </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="income.php"><span class="fa fa-money fa-fw"></span>Przychód </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="expense.php"><span class="fa fa-shopping-cart fa-fw"></span>Wydatek </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="balance.php"><span class="fa fa-pie-chart fa-fw"></span>Bilans </a>
					</li>
					<li class="nav-item-active">
						<div class="nav-link"><span class="fa fa-wrench fa-fw"></span>Ustawienia </div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="logout.php"><span class="fa fa-sign-out fa-fw"></span>Wyloguj </a>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	
	<main>
		<!-- Incomes Editor -->
		<div class="settings_group offset-xl-3 offset-lg-2 offset-md-1 offset-sm-2 offset-1 col-xl-3 col-lg-4 col-md-5 col-sm-8 col-10">
			<h4>
				Przychody
			</h4>
			<button class="btn_settings shadow">
				<a class="nav-link" href="#"><i class="fa fa-plus fa-fw"></i>Dodaj kategorię</a>
			</button>
			<button class="btn_settings shadow">
				<a class="nav-link" href="#"><i class="fa fa-pencil fa-fw"></i> Edytuj kategorię</a>
			</button>
			<button class="btn_settings bg_del shadow">
				<a class="nav-link" href="#"><i class="fa fa-trash fa-fw"></i> Usuń kategorię</a>
			</button>
		</div>
		<!-- Expenses Editor -->
		<div class="settings_group offset-1 offset-sm-2 offset-md-0 col-xl-3 col-lg-4 col-md-5 col-sm-8 col-10">
			<h4>
				Wydatki
			</h4>
			<button class="btn_settings shadow">
				<a class="nav-link" href="#"><i class="fa fa-plus fa-fw"></i>Dodaj kategorię</a>
			</button>
			<button class="btn_settings shadow">
				<a class="nav-link" href="#"><i class="fa fa-pencil fa-fw"></i> Edytuj kategorię</a>
			</button>
			<button class="btn_settings bg_del shadow">
				<a class="nav-link" href="#"><i class="fa fa-trash fa-fw"></i> Usuń kategorię</a>
			</button>
		</div>
		<!-- Payment Editor -->
		<div class="settings_group offset-xl-3 offset-lg-2 offset-md-1 offset-sm-2 offset-1 col-xl-3 col-lg-4 col-md-5 col-sm-8 col-10">
			<h4>
				Sposób płatności
			</h4>
			<button class="btn_settings shadow">
				<a class="nav-link" href="#"><i class="fa fa-plus fa-fw"></i>Dodaj kategorię</a>
			</button>
			<button class="btn_settings shadow">
				<a class="nav-link" href="#"><i class="fa fa-pencil fa-fw"></i> Edytuj kategorię</a>
			</button>
			<button class="btn_settings bg_del shadow">
				<a class="nav-link" href="#"><i class="fa fa-trash fa-fw"></i> Usuń kategorię</a>
			</button>
		</div>
		<!-- User Data Editor -->	
		<div class="settings_group offset-1 offset-sm-2 offset-md-0 col-xl-3 col-lg-4 col-md-5 col-sm-8 col-10">
			<h4>
				Dane użytkownika
			</h4>
			<button class="btn_settings shadow">
				<a class="nav-link" href="#"><i class="fa fa-user fa-fw"></i><i class="fa fa-refresh fa-fw"></i> Zmień imię</a>
			</button>
			<button class="btn_settings shadow">
				<a class="nav-link light" href="#"><i class="fa fa-envelope fa-fw"></i><i class="fa fa-refresh fa-fw"></i> Zmień e-mail</a>
			</button>
			<button class="btn_settings shadow">
				<a class="nav-link" href="#"><i class="fa fa-lock fa-fw"></i><i class="fa fa-refresh fa-fw"></i>Zmień hasło</a>
			</button>
		</div>
		<!-- Account Removing -->
		<div class="settings_group last_group offset-xl-3 offset-lg-2 offset-md-1 offset-sm-2 offset-1 col-xl-6 col-lg-8 col-md-10 col-sm-8 col-10">
			<button class="btn_settings bg_del shadow">
				<a class="nav-link" href="#"><i class="fa fa-user-times fa-fw"></i>Usuń konto</a>
			</button>
		</div>
		
		<br />
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
</body>
</html>