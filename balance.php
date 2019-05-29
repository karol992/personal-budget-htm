<?php

	session_start();
	
	if (!isset($_SESSION['id'])) {
		header('Location: index.php');
		exit();
	}
/****LOAD*BALANCE*PERIOD****************************************************************/	
	$date = new DateTime();
	
	//set default balance period
	if (!isset($_SESSION['balance_start_day']) || 
	!isset($_SESSION['balance_end_day'])) {
		$_SESSION['balance_start_day'] = $date->format('Y-m-01');
		$_SESSION['balance_end_day'] = $date->format('Y-m-t');
	}
	
	//load dropdown balance period
	if (isset($_POST['balance_period'])) {
		switch ($_POST['balance_period']) {
			case 'current_month': 
				$_SESSION['balance_start_day'] = $date->format('Y-m-01');
				$_SESSION['balance_end_day'] = $date->format('Y-m-t');
				break;
			case 'last_month':
				$date->modify('-1 month');
				$_SESSION['balance_start_day'] = $date->format('Y-m-01');
				$_SESSION['balance_end_day'] = $date->format('Y-m-t');
				break;
			case 'current_year': 
				$_SESSION['balance_start_day'] = $date->format('Y-01-01');
				$_SESSION['balance_end_day'] = $date->format('Y-12-31');
				break;
		}
		unset($_POST['balance_period']);
	}
	
	//load custom(modal) balance period
	if(isset($_POST['balance_start_day']) && isset($_POST['balance_end_day'])) {
		$_SESSION['balance_start_day'] = $_POST['balance_start_day'];
		$_SESSION['balance_end_day'] = $_POST['balance_end_day'];
		unset($_POST['balance_start_day']);
		unset($_POST['balance_end_day']);
	}
	
	//convert period date for ribbon content
	$startDate = new DateTime($_SESSION['balance_start_day']);
	$endDate = new DateTime($_SESSION['balance_end_day']);
	$_SESSION['balance_start_day_ribbon'] = $startDate->format('d.m.Y');
	$_SESSION['balance_end_day_ribbon'] = $endDate->format('d.m.Y');
/***LOAD*INCOME*SUMS****************************************************************/	
	require_once "database.php";
	
	//load sums of incomes in incomes_category_assigned_to_users
	$queryIncomes=$db->prepare("
	SELECT icat.name, SUM(ic.amount)
	FROM incomes ic
	INNER JOIN incomes_category_assigned_to_users icat
	ON ic.income_category_assigned_to_user_id = icat.id
	AND (ic.date_of_income BETWEEN :start AND :end)
	AND icat.id IN (
		SELECT icat.id FROM incomes_category_assigned_to_users icat
		INNER JOIN users
		ON users.id = icat.user_id
		AND users.id = :id
	)
	GROUP BY icat.id
	ORDER BY SUM(ic.amount) DESC;");
	$queryIncomes->bindValue(':start',$_SESSION['balance_start_day'],PDO::PARAM_STR);
	$queryIncomes->bindValue(':end',$_SESSION['balance_end_day'],PDO::PARAM_STR);
	$queryIncomes->bindValue(':id',$_SESSION['id'],PDO::PARAM_INT);
	$queryIncomes->execute();
	$incomes=$queryIncomes->fetchAll();
	
	//load all incomes_category_assigned_to_users
	$queryIncomeCategories=$db->prepare("
	SELECT name FROM incomes_category_assigned_to_users icat
	INNER JOIN users
	ON users.id = icat.user_id
	AND users.id = :id");
	$queryIncomeCategories->bindValue(':id',$_SESSION['id'],PDO::PARAM_INT);
	$queryIncomeCategories->execute();
	$incomeCategories=$queryIncomeCategories->fetchAll();
	
	//fill incomes with zero sums, <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<IT WAS HARD TO SOLVE
	foreach($incomeCategories as $ic) {
		$key = array_search($ic['name'], array_column($incomes, 'name')); //search the $incomes for a every incomes_category_assigned_to_users
		if(strlen((string)$key)==0) { //that way because of [0] in array; isset, isnull, empty was useless here
			$temp_array=array( 'name' => $ic['name'], 0=> $ic['name'] ,'SUM(ic.amount)' => 0.00,  1=> 0.00 );
			array_push($incomes, $temp_array);
		}
		unset($key);
	}
/***LOAD*EXPENSE*SUMS****************************************************************/		
	//load sums of expenses in expenses_category_assigned_to_users
	$queryExpenses=$db->prepare("
	SELECT ecat.name, SUM(ex.amount)
	FROM expenses ex
	INNER JOIN expenses_category_assigned_to_users ecat
	ON ex.expense_category_assigned_to_user_id = ecat.id
	AND (ex.date_of_expense BETWEEN :start AND :end)
	AND ecat.id IN (
		SELECT ecat.id FROM expenses_category_assigned_to_users ecat
		INNER JOIN users
		ON users.id = ecat.user_id
		AND users.id = :id
	)
	GROUP BY ecat.id
	ORDER BY SUM(ex.amount) DESC;");
	$queryExpenses->bindValue(':start',$_SESSION['balance_start_day'],PDO::PARAM_STR);
	$queryExpenses->bindValue(':end',$_SESSION['balance_end_day'],PDO::PARAM_STR);
	$queryExpenses->bindValue(':id',$_SESSION['id'],PDO::PARAM_INT);
	$queryExpenses->execute();
	$expenses=$queryExpenses->fetchAll();
	
	//load all expenses_category_assigned_to_users
	$queryExpenseCategories=$db->prepare("
	SELECT name FROM expenses_category_assigned_to_users ecat
	INNER JOIN users
	ON users.id = ecat.user_id
	AND users.id = :id");
	$queryExpenseCategories->bindValue(':id',$_SESSION['id'],PDO::PARAM_INT);
	$queryExpenseCategories->execute();
	$expenseCategories=$queryExpenseCategories->fetchAll();
	
//fill expenses with zero sums, <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<IT WAS HARD TO SOLVE
	foreach($expenseCategories as $ec) {
		$key = array_search($ec['name'], array_column($expenses, 'name')); //search the $expenses for a every expenses_category_assigned_to_users
		if(strlen((string)$key)==0) { //that way because of [0] in array; isset, isnull, empty was useless here
			$temp_array=array( 'name' => $ec['name'], 0=> $ec['name'] ,'SUM(ex.amount)' => 0.00,  1=> 0.00 );
			array_push($expenses, $temp_array);
		}
		unset($key);
	}

/***PIE*CHART****************************************************************/		
//$expenses_json = json_encode($expenses);

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
	<link rel="stylesheet" href="css/piechart.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/balance.css">
	
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
					<li class="nav-item-active">
						<div class="nav-link"><span class="fa fa-pie-chart fa-fw"></span>Bilans </div>
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
	
	<main>

		<div class="container"> <!-- Choose period of balance -->
				
				<div class="dropdown">
					 <button class="con margin20 offset-lg-10 offset-md-9 offset-sm-8 offset-7 col-lg-2 col-md-3 col-sm-4 col-5 btn btn-balance dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Zakres
					</button>
					<div class="dropdown-menu col-lg-2 col-md-3 col-sm-4 col-5" aria-labelledby="dropdownMenu2">
							<form action="balance.php" method="post">
							<button class="dropdown-item" type="submit" name="balance_period" value="current_month">Bieżący miesiąc</button>
							<button class="dropdown-item" type="submit" name="balance_period" value="last_month">Poprzedni miesiąc</button>
							<button class="dropdown-item" type="submit" name="balance_period" value="current_year">Bieżący rok</button></form>
						<button class="dropdown-item" type="button" href="#dateModal" data-toggle="modal" data-target="#dateModal">
							Niestandardowy
						</button>			
					</div>
				</div>
			
		</div>
		
		<div class="info_ribbon"> <!-- Display period of balance -->
			<div class="inB">Zakres bilansu:</div>
			<div class="inB">
				<div class="inB"><?php echo $_SESSION['balance_start_day_ribbon']; ?></div>
				<div class="inB"> - </div>
				<div class="inB"><?php  echo $_SESSION['balance_end_day_ribbon']; ?></div>
			</div>
		</div>
		
		<section> <!-- Contains incomes, expense pie chart, expenses. -->
			
			<div class="container"> 
			
				<div class="row">

					<div class="col-md-6"> 
							Przychody	
						<div id="income_table"><!-- Incomes categories. -->
							<?php
								$totalIncome=(float)0;
								foreach($incomes as $inc) {
									
									$incomeName=$inc['name'];
									$incomeSumValue=number_format((float)$inc['SUM(ic.amount)'], 2, '.', ''); //always show 2 decimal places
									$totalIncome+=$incomeSumValue;
echo <<<END
							<div class="b_line row shadow">
								<div class="blcell col-7">$incomeName</div>
								<div class="brcell col-4">$incomeSumValue</div>
								<button class="btn btn_list col-1" href="#listModal" data-toggle="modal" data-target="#listModal">
									<span class="fa fa-file-text-o"></span>
								</button>
							</div>
END;
								}
							?>
						</div>
	
						Wykres wydatków
						<div class="b_border shadow"> <!-- Pie chart with expenses. -->
							<div class="ratioparent">
								<div id="chartdiv" class="ratiochild"></div>
							</div>
						</div>
						<div> <!-- External pie chart legend. Internal has non-rwd display-->
							<button class="btn b_border col-12" id="legend_btn" href="#legendModal" data-toggle="modal" data-target="#legendModal">
								Legenda
							</button>
						</div>
		
					</div>
					
					<div class="col-md-6"> 
						Wydatki:
						<div id="expense_table"><!-- Expenses categories. -->
							<?php
								$totalExpense=(float)0;
								foreach($expenses as $ex) {
									$expenseName=$ex['name'];
									$expenseSumValue=number_format((float)$ex['SUM(ex.amount)'], 2, '.', ''); //always show 2 decimal places
									$totalExpense+=$expenseSumValue;
echo <<<END
							<div class="b_line row shadow">
								<div class="blcell col-7">$expenseName</div>
								<div class="brcell col-4">$expenseSumValue</div>
								<button class="btn btn_list col-1" href="#listModal" data-toggle="modal" data-target="#listModal">
									<span class="fa fa-file-text-o"></span>
								</button>
							</div>
END;
								}
							?>
						</div>
					</div>
					 
				</div>
			
			</div>
			
		</section>
		
		<footer> <!-- Sum & Comment. -->
		
			<div class="container"> 
			
				<div class="row">
					
					<div class="col-md-6">
						<div class="b_line b_sum row shadow">
							<div class="blcell col-7">Bilans</div>
							<div class="brcell col-5"><?php				
								$total=$totalIncome-$totalExpense;
								echo number_format((float)($total), 2, '.', ''); 
							?></div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="b_line b_motivation shadow">
							<?php
							if ($total>=0) {
echo <<<END
							<div class="inB"><span>Gratulacje. </span></div>
							<div class="inB"><span>Świetnie zarządzasz finansami!</span></div>
END;
							} else {
echo <<<END
							<div class="inB" style="color:red"><span>Uważaj, </span></div>
							<div class="inB" style="color:red"><span>wpadasz w długi!</span></div>
END;
							}
							?>
							
						</div>
					</div>
				
				</div>
			
			</div>
			
		</footer>
		
	</main>
	
<!------dateModal----------------------------------------------------------------------------------->	
	<div class="modal fade" id="dateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="balance.php" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<h5 class="modal-title">Niestandardowy okres</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<section>
							<div class="container">
								<div class="row">
									<label class="offset-1 col-2" for="balance_start_day">Od: </label>
									<input class="col-7" type="date" name="balance_start_day" value="<?php echo $_SESSION['balance_start_day']; ?>">
								</div>
								<div class="row">
									<label class="offset-1 col-2" for="balance_end_day">Do: </label>
									<input class="col-7" type="date" name="balance_end_day" value="<?php echo $_SESSION['balance_end_day']; ?>">
								</div>
							</div>
						</section>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-balance" value="Submit">OK</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<!------exampleListModal---------------------------------------------------------------------------->	
	<div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-labelledby="listModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<form class="modal-content" action="" method="post" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Szczegółowa lista</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<section>
						<div>Nazwa kategorii</div>
						<div class="container">
								<div class="modal_line row shadow">
									<input type="number" class="modal_cell col-12 col-sm-6 col-lg-3" step="0.01" value="2000.00" min="0.01">
									<input type="date" class="modal_cell col-12 col-sm-6 col-lg-3" value="2019-01-31">
									<input type="text" class="modal_cell col-12 col-lg-6" placeholder="Notatki..." onfocus="this.placeholder=''Notatki..." onblur="this.placeholder='Notatki...'" value="">
								</div>
								<div class="modal_line row shadow">
									<input type="number" class="modal_cell col-12 col-sm-6 col-lg-3" step="0.01" value="2000.00" min="0.01">
									<input type="date" class="modal_cell col-12 col-sm-6 col-lg-3" value="2019-01-31">
									<input type="text" class="modal_cell col-12 col-lg-6" placeholder="Notatki..." onfocus="this.placeholder=''Notatki..." onblur="this.placeholder='Notatki...'" value="">
								</div>
								<div class="modal_line row shadow">
									<input type="number" class="modal_cell col-12 col-sm-6 col-lg-3" step="0.01" value="2000.00" min="0.01">
									<input type="date" class="modal_cell col-12 col-sm-6 col-lg-3" value="2019-01-31">
									<input type="text" class="modal_cell col-12 col-lg-6" placeholder="Notatki..." onfocus="this.placeholder=''Notatki..." onblur="this.placeholder='Notatki...'" value="">
								</div>
								<div class="modal_line row shadow">
									<input type="number" class="modal_cell col-12 col-sm-6 col-lg-3" step="0.01" value="2000.00" min="0.01">
									<input type="date" class="modal_cell col-12 col-sm-6 col-lg-3" value="2019-01-31">
									<input type="text" class="modal_cell col-12 col-lg-6" placeholder="Notatki..." onfocus="this.placeholder=''Notatki..." onblur="this.placeholder='Notatki...'" value="">
								</div>
								<div class="modal_line row shadow">
									<input type="number" class="modal_cell col-12 col-sm-6 col-lg-3" step="0.01" value="2000.00" min="0.01">
									<input type="date" class="modal_cell col-12 col-sm-6 col-lg-3" value="2019-01-31">
									<input type="text" class="modal_cell col-12 col-lg-6" placeholder="Notatki..." onfocus="this.placeholder=''Notatki..." onblur="this.placeholder='Notatki...'" value="">
								</div>
						</div>
					</section>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-balance" data-dismiss="modal" value="Submit">OK</button>
				</div>
			</form>
		</div>
	</div>
<!---legendModal------------------------------------------------------------------------------------------>
	<div class="modal fade" id="legendModal" tabindex="-1" role="dialog" aria-labelledby="legendModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content" action="" method="post" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title" id="legendModalLabel">Legenda</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="legendwrapper">
						<div id="legenddiv"></div>
					</div>
				</div>
				
			</form>
		</div>
	</div>
<!-------------------------------------------------------------------------------------------------------------------------->

	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
	
	<script src="https://www.amcharts.com/lib/4/core.js"></script>
	<script src="https://www.amcharts.com/lib/4/charts.js"></script>
	<script src="http://www.amcharts.com/lib/4/themes/kelly.js"></script>
	<script src="js/piechart.js"></script>
	
</body>
</html>