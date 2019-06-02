<?php

	session_start();
	
	if (!isset($_SESSION['id'])) {
		header('Location: index.php');
		exit();
	}
	
	$_SESSION['modalTarget']=$_POST['modalTarget'];
	$action=$_POST['action'];
	$incomeId=$_POST['incomeId'];
	
	require_once "database.php";
	
	if($action=="delete") {
		$queryDeleteIncome=$db->prepare('
		DELETE FROM incomes WHERE incomes.id = :incomeId
		');
		$queryDeleteIncome->bindValue(':incomeId',$incomeId,PDO::PARAM_INT);
		$queryDeleteIncome->execute();
	} else if ($action=="update") {
		$amount=$_POST['amount'];
		$date=$_POST['date'];
		$comment=$_POST['comment'];
		$queryUpdateIncome=$db->prepare('
		UPDATE incomes SET amount = :amount, date_of_income = :date, income_comment = :comment WHERE incomes.id = :incomeId
		');
		$queryUpdateIncome->bindValue(':amount',$amount,PDO::PARAM_INT);
		$queryUpdateIncome->bindValue(':date',$date,PDO::PARAM_INT);
		$queryUpdateIncome->bindValue(':comment',$comment,PDO::PARAM_INT);
		$queryUpdateIncome->bindValue(':incomeId',$incomeId,PDO::PARAM_INT);
		$queryUpdateIncome->execute();
	}

	header("Location: balance.php");
	exit();
?>