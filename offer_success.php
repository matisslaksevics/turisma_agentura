<?php
  require_once "connection.php";  // piesauc connection.php failu
  session_start(); // sesijas sākums
  if(!isset($_SESSION['account'])) { // pārbaude - vai sesija ir aktīva
    $_SESSION['error'] = "Please login to access the website!";// kļūdas ziņojums
    header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
  }
  if(isset($_GET['ID'])) {
    $offer_id = $_GET['ID'];
    $select_stmt = $db->prepare("SELECT * FROM celojumi WHERE ID = :id");
    $select_stmt->bindParam(":id", $offer_id);
    $select_stmt->execute();
    if ($select_stmt->rowCount() > 0) {
      while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['ID'];
        $nosaukums = $row['Nosaukums'];
        $sakuma_diena = $row['SakumaDiena'];
        $beigu_diena = $row['BeiguDiena'];
        $transports = $row['Transports'];
        $kompanija = $row['Kompanija'];
        $valsts = $row['Valsts'];
        $pilseta = $row['Pilseta'];
        $cena1per = $row['Cena1per'];
      }
    }
  }
  if(isset($_GET['price'])){ // funkcija - pirkuma reģistrēšana datu bāzē
    $person = $_GET['price'] / $cena1per; // personu skaitu iegūst izdalot pilno summu ar cena1per
    $cena = $_GET['price'];
    $trip = $id;
    $client = $_SESSION['id_user'];
    $insert_stmt = $db->prepare("INSERT INTO ieperk(Cena, PersonuSkaits, Celojums, Lietotajs) VALUES(:cena, :personas, :celojums, :lietotajs)"); // tiek izveidots INSERT query
    $insert_stmt->bindParam(":cena", $cena);
    $insert_stmt->bindParam(":personas", $person);
    $insert_stmt->bindParam(":celojums", $trip);
    $insert_stmt->bindParam(":lietotajs", $client);
    $insert_stmt->execute();
  }
?>
<html> <!-- html kods zemāk -->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Success</title>
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body class="bg-dark text-white">
    <div class="container">
      Thank you for purchasing the trip <?php ?><br>
      You may return back to the website!<br>
      <!-- poga kas aizved lietotāju atpakaļ uz ceļojumu kataloga lapu -->
      <a class="btn btn-outline-info" name="submit" style="margin: auto" href="offers.php">Home page</a>
    </div>
    <!-- zemāk - visi izmantotie skripti -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>