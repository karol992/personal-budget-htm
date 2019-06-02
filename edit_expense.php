<?php

	session_start();
	
	if (!isset($_SESSION['id'])) {
		header('Location: index.php');
		exit();
	}
	
	$_SESSION['modalTarget']=$_POST['modalTarget'];
	$action=$_POST['action'];
	$expenseId=$_POST['expenseId'];
	
	require_once "database.php";
	
	if($action=="delete") {
		$queryDeleteExpense=$db->prepare('
		DELETE FROM expenses WHERE expenses.id = :expenseId
		');
		$queryDeleteExpense->bindValue(':expenseId',$expenseId,PDO::PARAM_INT);
		$queryDeleteExpense->execute();
	} else if ($action=="update") {
		$amount=$_POST['amount'];
		$date=$_POST['date'];
		$payment=$_POST['payment'];
		$comment=$_POST['comment'];
		$queryUpdateExpense=$db->prepare('
		UPDATE expenses SET amount = :amount, date_of_expense = :date, expense_comment = :comment WHERE expenses.id = :expenseId
		');
		$queryUpdateExpense->bindValue(':amount',$amount,PDO::PARAM_INT);
		$queryUpdateExpense->bindValue(':date',$date,PDO::PARAM_INT);
		$queryUpdateExpense->bindValue(':comment',$comment,PDO::PARAM_INT);
		$queryUpdateExpense->bindValue(':expenseId',$expenseId,PDO::PARAM_INT);
		$queryUpdateExpense->execute();
	}
	
	//echo $action.": ".$expenseId." (".$amount." ".$date." ".$payment." ".$comment.") ";
	//echo "<script>window.close();</script>";

	header("Location: balance.php");
	exit();
?>