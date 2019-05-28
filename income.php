<?php

	session_start();
	
	if (!isset($_SESSION['id'])) {
		header('Location: index.php');
		exit();
	} 
	if (!isset($_SESSION['income_date'])) {
		$now=new DateTime();
		$income_date=$now->format('Y-m-d');
	}
	
	require_once "database.php";
	
	$queryIncomeCategories=$db->prepare("SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id=:id");
	$queryIncomeCategories->bindValue(':id',$_SESSION['id'],PDO::PARAM_INT);
	$queryIncomeCategories->execute();
	$incomeCategories=$queryIncomeCategories->fetchAll();
	
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
	<link rel="stylesheet" href="css/income.css">
	
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
					<li class="nav-item-active">
						<div class="nav-link"><span class="fa fa-money fa-fw"></span>Przychód </div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="expense.php"><span class="fa fa-shopping-cart fa-fw"></span>Wydatek </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="balance.php"><span class="fa fa-pie-chart fa-fw"></span>Bilans </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="settings.php"><span class="fa fa-wrench fa-fw"></span>Ustawienia </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="logout.php"><span class="fa fa-sign-out fa-fw"></span>Wyloguj </a>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	<?php 
	if (isset($_SESSION['previousIncome'])) {
		echo $_SESSION['previousIncome'];
		unset($_SESSION['previousIncome']);
	}
	?>
	<main>
		<!-- Income adding -->
		<form class="container offset-xl-3 offset-lg-2 offset-md-1 offset-sm-2 offset-1 col-xl-6 col-lg-8 col-md-10 col-sm-8 col-10" action="add_income.php" method="post" enctype="multipart/form-data">
			<!-- Amount of income -->
			<div class="income_section col-12 col-md-6">
				<div><label for="income_value">Kwota: </label></div>
				<div><input type="number" name="income_value" step="0.01" min="0.01" max="999999.99" required></div>
			</div>
			<!-- Date of income -->
			<div class="income_section col-12 col-md-6">
				<div><label for="income_date">Data: </label></div>
				<div><input type="date" name="income_date" value="<?php
				echo $income_date;
				?>"></div>
			</div>
			<!-- Category of income -->
			<div class="income_section col-12 col-md-6">
				<div>Kategoria: </div>
				<select name="income_category">
					
					<?php
						$isFirstIC=true;
						foreach ($incomeCategories as $incCaty) {
							if ($isFirstIC) {
								echo '<option value="'.$incCaty['id'].'" selected> '.$incCaty['name'].' </option>';
								$isFirstIC=false;
							} else {
								echo '<option value="'.$incCaty['id'].'"> '.$incCaty['name'].' </option>';
							}
						}
						?>
				</select>
			</div>
			<!-- Income note -->
			<div class="income_section col-12 col-md-6">
				<label for="income_note">Notatki: </label>
				<input type="textarea" name="income_note" placeholder="Opcjonalnie..." onfocus="this.placeholder=''" onblur="this.placeholder='Opcjonalnie...'"
				value="" maxlength="100">
			</div>
			<!-- Income saving -->
			<div class="income_btn offset-4 offset-md-6 col-4 col-md-3">
				<button class="btn btn-success" type="submit" value="Submit">Dodaj</button>
			</div>
			<div class="income_btn col-4 col-md-3">
				<button class="btn btn-danger" type="reset" value="Reset">Anuluj</button>
			</div>
		</form>
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
</body>
</html>