<?php

	session_start();
	
	if (!isset($_SESSION['id'])) {
		header('Location: index.php');
		exit();
	}
	if ((!isset($_POST['income_value'])) || 
	(!isset($_POST['income_date'])) || 
	(!isset($_POST['income_category'])) || 
	(!isset($_POST['income_note']))) {
		$_SESSION['previousIncome'] = '<div class="info_ribbon" style="color: red">
			Nie dodano przychodu!
		</div>';
		header('Location: income.php');
		exit();
	}
	
	/*if (isset($_POST['income_value'])) echo $_POST['income_value'].'<br />';
	if (isset($_POST['income_date'])) echo $_POST['income_date'].'<br />';
	if (isset($_POST['income_category'])) echo $_POST['income_category'].'<br />';
	if (isset($_POST['income_note'])) echo $_POST['income_note'].'<br />';*/
	
	$user_id = $_SESSION['id'];
	$income_value = $_POST['income_value'];
	$income_date = $_POST['income_date'];
	$income_category = $_POST['income_category'];
	$income_note = $_POST['income_note'];
	
	require_once "database.php";
	
	$queryIncome = $db->prepare("INSERT INTO incomes (id, user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
	VALUES (NULL, :user_id, :category_id, :value, :date, :comment)");
	$queryIncome->bindValue(':user_id', $user_id, PDO::PARAM_INT);
	$queryIncome->bindValue(':category_id', $income_category, PDO::PARAM_INT);
	$queryIncome->bindValue(':value', $income_value, PDO::PARAM_STR);
	$queryIncome->bindValue(':date', $income_date, PDO::PARAM_STR);
	$queryIncome->bindValue(':comment', $income_note, PDO::PARAM_STR);
	$queryIncome->execute();
	
	$queryCategory=$db->prepare("SELECT name FROM incomes_category_assigned_to_users WHERE id=:category_id");
	$queryCategory->bindValue(':category_id', $income_category, PDO::PARAM_INT);
	$queryCategory->execute();
	$categoryName=$queryCategory->fetch();
	
	$_SESSION['previousIncome'] = '<div class="info_ribbon">
		<div class="inB">Dodano +'.$categoryName['name']." ".$income_value.'zł</div>
		<div class="inB"> ('.$income_date.')</div>
	</div>';
	header('Location: income.php');
	exit();