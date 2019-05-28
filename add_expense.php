<?php

	session_start();
	
	if (!isset($_SESSION['id'])) {
		header('Location: index.php');
		exit();
	}
	/*if (isset($_POST['expense_value'])) echo $_POST['expense_value'].'<br />';
	if (isset($_POST['expense_date'])) echo $_POST['expense_date'].'<br />';
	if (isset($_POST['expense_category'])) echo $_POST['expense_category'].'<br />';
	if (isset($_POST['payment_category'])) echo $_POST['payment_category'].'<br />';
	if (isset($_POST['expense_note'])) echo $_POST['expense_note'].'<br />';*/
	
	
	if ((!isset($_POST['expense_value'])) || 
	(!isset($_POST['expense_date'])) || 
	(!isset($_POST['expense_category'])) || 
	(!isset($_POST['payment_category'])) ||
	(!isset($_POST['expense_note']))) {
		$_SESSION['previousExpense'] = '<div class="info_ribbon" style="color: red">
			Nie dodano wydatku!
		</div>';
		header('Location: expense.php');
		exit();
	}
	
	$_SESSION['expense_date']=$_POST['expense_date'];
	
	$user_id = $_SESSION['id'];
	$expense_value = $_POST['expense_value'];
	$expense_date = $_SESSION['expense_date'];
	$expense_category = $_POST['expense_category'];
	$payment_category = $_POST['payment_category'];
	$expense_note = $_POST['expense_note'];
	
	require_once "database.php";
	
	$queryexpense = $db->prepare("INSERT INTO expenses (id, user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
	VALUES (NULL, :user_id, :category_id, :payment_cat_id, :value, :date, :comment)");
	$queryexpense->bindValue(':user_id', $user_id, PDO::PARAM_INT);
	$queryexpense->bindValue(':category_id', $expense_category, PDO::PARAM_INT);
	$queryexpense->bindValue(':payment_cat_id', $payment_category, PDO::PARAM_INT);
	$queryexpense->bindValue(':value', $expense_value, PDO::PARAM_STR);
	$queryexpense->bindValue(':date', $expense_date, PDO::PARAM_STR);
	$queryexpense->bindValue(':comment', $expense_note, PDO::PARAM_STR);
	$queryexpense->execute();
	
	$queryCategory=$db->prepare("SELECT name FROM expenses_category_assigned_to_users WHERE id=:category_id");
	$queryCategory->bindValue(':category_id', $expense_category, PDO::PARAM_INT);
	$queryCategory->execute();
	$categoryName=$queryCategory->fetch();
	
	$_SESSION['previousExpense'] = '<div class="info_ribbon">
		<div class="inB">Dodano '.$categoryName['name']." -".$expense_value.'zł</div>
		<div class="inB"> ('.$expense_date.')</div>
	</div>';
	header('Location: expense.php');
	exit();