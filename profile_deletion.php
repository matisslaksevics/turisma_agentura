<?php
session_start(); // sesijas sākums
require_once 'connection.php'; // piesauc connection.php failu
if (isset($_GET['ID'])) { // funkcija - tiek izdzēsts lietotājs ar ID kas tiek padots 
    $user_id = $_GET['ID'];
    $delete_stmt = $db->prepare("DELETE FROM lietotaji WHERE ID = :id"); // tiek izveidots DELETE query
    $delete_stmt->bindParam(":id", $user_id);
    $result = $delete_stmt->execute();
    if ($result == TRUE) { // pārbauda vai query ir izpildījies
      $_SESSION['error'] = "Your account has been deleted!"; 
      header("location: index.php");
      session_destroy(); // sessijas iznīcināšana
      exit();
    }
  }
?>
