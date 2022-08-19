<?php
  require_once 'connection.php';// piesauc connection.php failu
  session_start();// sesijas sākums
   if($_SESSION['account'] != "admin") { // pārbaude - vai lietotājs ir administrators
    $_SESSION['error'] = "You don't have the permission to this page!"; // kļūdas ziņojums
    header("location: home.php"); // lietotājs tiek pārvests uz home.php lapu
  }
  if(!isset($_SESSION['account'])) { // pārbaude - vai sesija ir aktīva
      $_SESSION['error'] = "Please login to access the website!";
      header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
    }
    // visi mainīgie tiek samainīti uz tukšumiem
  $offer_id = 0;
  $id = 0;
  $nosaukums = '';
  $sakuma_diena = '';
  $beigu_diena = '';
  $transports = '';
  $kompanija = '';
  $valsts = '';
  $pilseta = '';
  $cena1per = '';
  if (isset($_POST['update'])) { // funkcija - ceļojuma informācijas rediģēšana
    $offer_id = $_POST['offer_id']; // padotās izmaiņas ievadformās tiek saglabātas mainīgajos
    $nosaukums = $_POST['nosaukums'];
    $sakuma_diena = $_POST['sakuma_diena'];
    $beigu_diena = $_POST['beigu_diena'];
    $transports = $_POST['transports'];
    $kompanija = $_POST['kompanija'];
    $valsts = $_POST['valsts'];
    $pilseta = $_POST['pilseta'];
    $cena1per = $_POST['cena1per'];
    // notiek pārbaudes vai padotā informācija nav default vērtības
    if($transports == 'Select transport') {
      $_SESSION['mistake'] = 'Invalid transport option!';
      header('Location: admin_offers.php');
      exit();
    } else if($beigu_diena <= $sakuma_diena) {
      $_SESSION['mistake'] = 'Invalid offer end date!';
      header('Location: admin_offers.php');
      exit();
    } else if($kompanija == 'Select transport company') {
      $_SESSION['mistake'] = 'Invalid transport company!';
      header('Location: admin_offers.php');
      exit();
    } else if($valsts == 'Select country') {
      $_SESSION['mistake'] = 'Invalid country option!';
      header('Location: admin_offers.php');
      exit();
    } else if($pilseta == 'Select city') {
      $_SESSION['mistake'] = 'Invalid city option!';
      header('Location: admin_offers.php');
      exit();
    } else {
      $update_stmt = $db->prepare("UPDATE celojumi SET Nosaukums = :nosaukums, SakumaDiena = :sakuma_diena, BeiguDiena = :beigu_diena,Kompanija = :kompanija, Transports = :transports, Valsts = :valsts, Pilseta = :pilseta,  Cena1Per = :cena1per WHERE ID = :id"); // tiek izveidots UPDATE query
      $update_stmt->bindParam(":nosaukums", $nosaukums);
      $update_stmt->bindParam(":sakuma_diena", $sakuma_diena);
      $update_stmt->bindParam(":beigu_diena", $beigu_diena);
      $update_stmt->bindParam(":transports", $transports);
      $update_stmt->bindParam(":kompanija", $kompanija);
      $update_stmt->bindParam(":valsts", $valsts);
      $update_stmt->bindParam(":pilseta", $pilseta);
      $update_stmt->bindParam(":cena1per", $cena1per);
      $update_stmt->bindParam(":id", $offer_id);
      $result = $update_stmt->execute();
      if ($result == TRUE) { // pārbauda vai query ir izpildījies
        $_SESSION['success'] = 'Record edited successfully!';
        header('Location: admin_offers.php');
        exit();
      }
    }
  }
  if (isset($_GET['ID'])) { // funkcija - ceļojuma esošās informācijas izvade
    $offer_id = $_GET['ID'];
    $select_stmt = $db->prepare("SELECT * FROM celojumi WHERE ID = :id"); // tiek izveidots select query
    $select_stmt->bindParam(":id", $offer_id);
    $select_stmt->execute();
    if ($select_stmt->rowCount() > 0) { // tiek parbaudits vai ir izvadīti ieraksti
      while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) { // select query tiek izvadits kā daudz-dimensiju masīvs un katra kolonna tiek saglabāta mainīgajā - iet cauri katram ierakstam ar 'while' ciklu
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
?>
<html> <!-- html kods zemāk -->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Offers</title>
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark">
      <a class="navbar-brand" href="#">Tourism</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="admin_home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="offers.php">Offers<span class="sr-only"></span></a>
          </li>
          <?php if ($_SESSION['account'] == "admin"): ?> <!-- drop-down izvelne priekš administratora paneliem -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Admin panels
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="admin_offers.php">Offer manager</a>
              <a class="dropdown-item" href="account_panel.php">Account manager</a>
            </div>
          </li>
        <?php endif; ?>
        </ul>
        <form class="form-inline my-2 my-lg-0">
        <a class="nav-link" href="profile.php"><?php echo $_SESSION['first_name'];?></a>
        <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php">Logout</a>
      </form>
      </div>
    </nav>
    <div class="container">
      <?php if (isset($_SESSION['success'])): ?> <!-- div tagu priekšmets kurš tiek lietots priekš ziņojumu izvades pēc funkciju izpildēm -->
        <div class="alert alert-success">
          <h3>
            <?php 
              echo $_SESSION['success'];
              unset($_SESSION['success']);
            ?>
          </h3>
        </div>
      <?php endif ?>
      <?php if (isset($_SESSION['mistake'])): ?>
        <div class="alert alert-danger">
          <h3>
            <?php 
              echo $_SESSION['mistake'];
              unset($_SESSION['mistake']);
            ?>
          </h3>
        </div>
      <?php endif ?>
      <div class="col-md-5" style="margin: auto;">
      <h2>Offer Update Window</h2> <!-- virsraksts formai -->
      <form method="POST" enctype="multipart/form-data"> <!-- forma priekš ceļojuma informācijas rediģēšanas -->
        <input type="hidden" name="offer_id" value="<?php echo $offer_id; ?>">
        <br><label>Title</label> <!-- katrā ievadformā tiek padota informācija no speficiski izvēlētā ceļojuma -->
        <br><input type="text" class="form-control" name="nosaukums" placeholder="Enter offer title" required value="<?php echo $nosaukums; ?>">
        <br><label for="start">Start date</label>
        <br><input type="date" name="sakuma_diena" required value="<?php echo $sakuma_diena; ?>">
        <br><label for="start">End date</label>
        <br><input type="date" name="beigu_diena" required value="<?php echo $beigu_diena; ?>">
        <br><label>Transport</label>
        <select class="form-control" name="transports"> <!-- funkcijas - kas izveido drop-down izvelnes ar informāciju no specifiskām tabulām -->
          <option><?php echo $transports; ?></option>
          <?php
            $select_stmt = $db->prepare('SELECT DISTINCT Tips FROM transporti');
            $select_stmt->execute();
            while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
              $assoctitle = $row['Tips'];
              echo "<option value=" .$assoctitle. ">" .$assoctitle. "</option>";
            }
          ?>
        </select>
        <br><label>Company</label>
             <select class="form-control" name="kompanija">
              <option><?php echo $kompanija; ?></option>
                <?php
                  $select_stmt = $db->prepare("SELECT DISTINCT Nosaukums1 FROM kompanijas");
                  $select_stmt->execute();
                  while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $assoctitle = $row['Nosaukums1'];
                    echo "<option value=" .$assoctitle. ">" .$assoctitle. "</option>";
                  }
                ?>
            </select>
            <br><label>Country</label>
             <select class="form-control" name="valsts">
              <option><?php echo $valsts; ?></option>
                <?php
                  $select_stmt = $db->prepare("SELECT DISTINCT Nosaukums FROM valstis");
                  $select_stmt->execute();
                  while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $assoctitle = $row['Nosaukums'];
                    echo "<option value=" .$assoctitle. ">" .$assoctitle. "</option>";
                  }
                ?>
            </select>
            <br><label>City</label>
             <select class="form-control" name="pilseta">
              <option><?php echo $pilseta; ?></option>
                <?php
                  $select_stmt = $db->prepare("SELECT DISTINCT Nosaukums FROM pilsetas");
                  $select_stmt->execute();
                  while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $assoctitle = $row['Nosaukums'];
                    echo "<option value=" .$assoctitle. ">" .$assoctitle. "</option>";
                  }
                ?>
            </select>
        <br><label>Price for 1 person</label>
        <br><input type="text" class="form-control" name="cena1per" placeholder="Enter offer price (1 person)" required value="<?php echo $cena1per; ?>">
        <div class="form-group">
          <br><input name="update" value="Update Offer" type="submit" class="btn btn-primary form-control" onclick="checker()"><br> <!-- poga kas izpilda jauna ceļojuma rediģēšanas funkciju -->
        </div>
      </form>
    </div>
    </div>
    <!-- zemāk - visi izmantotie skripti -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- zemāk - skripts, kas parāda ziņojumu ar pieprasījumu lietotājam vai vēlas turpināt ar izmaiņām -->
    <script>
     function checker(){
        var result = confirm('Do you wish to save these changes?');
        if (result == false) {
          event.preventDefault();
        }
      }
    </script>
  </body>
</html>