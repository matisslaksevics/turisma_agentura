<?php
	$db_user = "root"; // tiek ievietota datu bāzes admin konta informācija
	$db_password = "";
	try {
		$db = new PDO("mysql:host=localhost;dbname=turisma_agentura;", $db_user, $db_password); // tiek izveidots savienojums ar datu bāzi
	} catch(PDOException $e) {
		$e->getMessage();
	}
?>