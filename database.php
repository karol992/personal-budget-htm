<?php
	$config = require_once('config.php');
	try {
		$db = new PDO("mysql:host={$config['host']};dbname={$config['database']};charset=utf8", $config['user'], $config['password'], [
			PDO::ATTR_EMULATE_PREPARES => false, 
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);
	} catch (PDOException $error) {
		/*$_SESSION['error'] = '<div class="input_error">Błąd serwera! Przepraszamy za niedogodności!</div>
		<div>Informacja developerska: '.$e.'</div>';
		header('Location: index.php');*/
		echo $error->getMessage();
		exit('Database error');
	}