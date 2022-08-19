<?php 
	session_start(); // sesijas sākums
	$_SESSION['error'] = "You have been logged out!"; // kļūdas ziņojums
	header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
	session_destroy(); // sesijas iznīcināšana
?>