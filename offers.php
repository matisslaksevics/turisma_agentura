<?php
  require_once "connection.php"; // piesauc connection.php failu
  session_start(); // sesijas sākums
  if(!isset($_SESSION['account'])) { // pārbaude - vai sesija ir aktīva
    $_SESSION['error'] = "Please login to access the website!"; // kļūdas ziņojums
    header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
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
    <nav class="navbar navbar-expand-lg navbar-dark"> <!-- nav bar kods apzīmēts ar 'nav' tagu -->
      <a class="navbar-brand" href="#">Tourism</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <?php if ($_SESSION['account'] == "client"): ?> <!-- funkcija - kas pārbauda vai lietotājs ir klients - un izvada pareizo home lapu -->
            <a class="nav-link" href="home.php">Home</a>
            <?php else: ?>
            <a class="nav-link" href="admin_home.php">Home</a>
          <?php endif; ?>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="offers.php">Offers<span class="sr-only">(current)</span></a>
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
        <a class="btn btn-outline" href="profile.php"><?php echo $_SESSION['first_name'];?></a> <!-- funkcija - izvada lietotāja vārdu kā hyperlink uz profila lapu -->
        <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php">Logout</a><!-- poga kurai ir logout funkcija -->
      </form>
      </div>
    </nav>
    <div class="container" style="margin: auto;">
      <h1>Offers</h1>
      <?php if (isset($_SESSION['success'])) : ?> <!-- div tagu priekšmets kurš tiek lietots priekš ziņojumu izvades pēc funkciju izpildēm -->
        <div class="alert alert-success">
          <h3>
            <?php 
              echo $_SESSION['success'];
              unset($_SESSION['success']);
            ?>
          </h3>
        </div>
      <?php endif ?>
      <?php if (isset($_SESSION['error'])) : ?>
        <div class="alert alert-danger">
          <h3>
            <?php 
              echo $_SESSION['error'];
              unset($_SESSION['error']);
            ?>
          </h3>
        </div>
      <?php endif ?>
      <br>
       <form action="offers.php" method="POST"> <!-- forma priekš ceļojumu kataloga filtriem -->
          <label>Filters</label>
          <input type="text" name="searchtitle" placeholder="Title"> <!-- tiek izveidotas ievades -->
          Start date <input type="date" name="searchstartdate" placeholder="Start Date">
          End date <input type="date" name="searchenddate" placeholder="End Date">
          <select  name="searchtransport"> <!-- funkcijas - kas izveido drop-down izvelnes ar informāciju no specifiskām tabulām -->
          <option>Transport</option>
          <?php
            $select_stmt = $db->prepare('SELECT DISTINCT Tips FROM transporti');
            $select_stmt->execute();
            while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
              $assoctitle = $row['Tips'];
              echo "<option value=" .$assoctitle. ">" .$assoctitle. "</option>";
            }
          ?>
        </select>
          <select  name="searchcompany">
              <option>Company</option>
                <?php
                  $select_stmt = $db->prepare("SELECT DISTINCT Nosaukums1 FROM kompanijas");
                  $select_stmt->execute();
                  while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $assoctitle = $row['Nosaukums1'];
                    echo "<option value=" .$assoctitle. ">" .$assoctitle. "</option>";
                  }
                ?>
            </select>
             <select name="searchcountry">
              <option>Country</option>
                <?php
                  $select_stmt = $db->prepare("SELECT DISTINCT Nosaukums FROM valstis");
                  $select_stmt->execute();
                  while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $assoctitle = $row['Nosaukums'];
                    echo "<option value=" .$assoctitle. ">" .$assoctitle. "</option>";
                  }
                ?>
            </select>
               <select name="searchcity">
              <option>City</option>
                <?php
                  $select_stmt = $db->prepare("SELECT DISTINCT Nosaukums FROM pilsetas");
                  $select_stmt->execute();
                  while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $assoctitle = $row['Nosaukums'];
                    echo "<option value=" .$assoctitle. ">" .$assoctitle. "</option>";
                  }
                ?>
            </select><br><br>
            <div style="margin: auto; float: center;">
            <input type="text" name="searchminprice" placeholder="Minimum price"><br>
            <input type="text" name="searchmaxprice" placeholder="Maximum price">
            <input type="submit" name="submit" class="btn btn-primary"><br><br> <!-- poga kas iekļauj filtrus ceļojumu izvadīšanā -->
            <input type="submit" name="AllOffers" value="All Offers" class="btn btn-primary"> <!-- poga kas izvada visus ceļojumus kas ir datu bāzē -->
          </div>
        </form>
         <?php // funkcija - visu ceļojumu izvade
   if (isset($_POST['AllOffers'])) {
  ?> 
    <?php
      $select_stmt = $db->prepare("SELECT * FROM celojumi");
      $select_stmt->execute();
      while($row = $select_stmt->fetch(PDO::FETCH_ASSOC)):
    ?>

    <div class="row">
    <div style="border: 4px solid darkorange; float:left; display: inline; border-radius: 5%; margin: auto;" class="col-lg-10"> <!-- tiek izveidota figūra priekš atsevišķu ceļojumu izvades -->
      <div class="row">
      <br>
      <div class ="col-md-4" style="margin: auto;">
        <h2 style="font-size:  150%;">Title - <?php echo $row['Nosaukums']; ?></h2>
      </div>
        <div class ="col-md-4" style="margin: auto;">
          <p style="font-size:  150%;">
            StartDate - <?php echo $row['SakumaDiena'] ?><br>
            EndDate - <?php echo $row['BeiguDiena'] ?><br>
            Transport - <?php echo $row['Transports'] ?><br>
            Transport company - <?php echo $row['Kompanija'] ?><br>
            Country - <?php echo $row['Valsts'] ?><br>
            City - <?php echo $row['Pilseta'] ?><br>
            Reizes pirkts - <?php
            $cid = $row['ID'];
            $skait_stmt = $db->prepare("SELECT COUNT(*) FROM ieperk as i INNER JOIN celojumi as c ON i.Celojums = c.ID WHERE i.Celojums = :cid;"); // funkcija - pirkumu reizes konkrētajam ceļojumam
            $skait_stmt->bindParam(":cid", $cid);
            $skait_stmt->execute();
            $skaitRow = $skait_stmt->fetch(PDO::FETCH_ASSOC);
            echo $skaitRow['COUNT(*)'];?><br>
          </p>
      </div>
           <div class ="col-md-4" style="margin: auto;">
        <h2 style="font-size:  150%;">Price (for 1 person) - €<?php echo $row['Cena1per']?></h2>
        <a class="btn btn-success" style="margin: auto" href="offer_checkout.php?ID=<?php echo $row['ID'];?>&person=1">Purchase</a> <!-- poga kas aizved lietotāju uz checkout lapu -->
        <br><br>
      </div>
      </div>
      </div>
    </div>
      <?php 
        endwhile;
      }
    if(isset($_POST['submit'])){ // funkcija - ceļojumu filtri
      $title = $_POST['searchtitle'];
      $startdate = $_POST['searchstartdate'];
      $enddate = $_POST['searchenddate'];
      $transport = "";
      $company = "";
      $city = "";
      $country = "";
      if($_POST['searchtransport'] != "Transport") {
        $transport = $_POST['searchtransport'];
      }
      if($_POST['searchcompany'] !="Company"){
        $company = $_POST['searchcompany'];
      }
      if($_POST['searchcountry'] != "Country"){
        $country = $_POST['searchcountry'];
      }
      if($_POST['searchcity'] != "City"){
        $city = $_POST['searchcity'];
      }
      $minprice = $_POST['searchminprice'];
      $maxprice = $_POST['searchmaxprice'];
      if($title != "" || $startdate != "" || $enddate != "" || $transport != "" || $company != "" || $country != "" || $city != "" || $minprice != "" || $maxprice != "") { // pārbaude - vai vismaz viens filtrs ir izmantots
        if($maxprice < $minprice){
          $_SESSION['error'] = 'Maximum price is lower than minimum price!';
          header('Location: offers.php');
        }
        if($minprice < 0){
          $_SESSION['error'] = 'Invalid minimum price!';
          header('Location: offers.php');
        }
        if($maxprice < 0) {
          $_SESSION['error'] = 'Invalid maximum price!';
          header('Location: offers.php');
        }
        $query = "SELECT * FROM celojumi WHERE "; // balstoties uz to - kuri filtri ir izmantoti - tiek veidots specifiskais SELECT query
        if($title != ""){
          $query .= " Nosaukums = '$title'";
        }
        if($startdate != ""){
          if(strpos($query, "=") !== FALSE){
            $query .= " AND SakumaDiena = '$startdate'";
          } else {
          $query .= " SakumaDiena = '$startdate'";
        }
      }
       if($enddate != ""){
          if(strpos($query, "=") !== FALSE){
            $query .= " AND BeiguDiena = '$enddate'";
          } else {
          $query .= " BeiguDiena = '$enddate'";
        }
      }
       if($transport != ""){
          if(strpos($query, "=") !== FALSE){
            $query .= " AND Transports = '$transport'";
          } else {
          $query .= " Transports = '$transport'";
        }
      }
        if($company != ""){
          if(strpos($query, "=") !== FALSE){
            $query .= " AND Kompanija = '$company'";
          } else {
          $query .= " Kompanija = '$company'";
        }
      }
        if($country != ""){
          if(strpos($query, "=") !== FALSE){
            $query .= " AND Valsts = '$country'";
          } else {
          $query .= " Valsts = '$country'";
        }
      }
        if($city != ""){
          if(strpos($query, "=") !== FALSE){
            $query .= " AND Pilseta = '$city'";
          } else {
          $query .= " Pilseta = '$city'";
        }
      }
       if($minprice != "" AND $maxprice != ""){
        if(strpos($query, "=") !== FALSE){
          $query .= " AND Cena1per BETWEEN '$minprice' AND '$maxprice'";
        } else {
          $query .= " Cena1per BETWEEN '$minprice' AND '$maxprice'";
        }
      } else {
        if($minprice != ""){
          if(strpos($query, "=") !== FALSE){
            $query .= " AND Cena1per BETWEEN '$minprice' AND 999999.99";
          } else {
            $query .= " Cena1per BETWEEN '$minprice' AND 999999.99";
          }
        }
        if($maxprice != ""){
          if(strpos($query, "=") !== FALSE){
            $query .= " AND Cena1per BETWEEN 0.00 AND '$maxprice'";
          } else {
            $query .= " Cena1per BETWEEN 0.00 AND '$maxprice'";
          }
        } 
      }
        $select_stmt = $db->prepare($query); // izveidotais query tiek izmantots
        $select_stmt->execute();
        if($select_stmt->rowCount() > 0){ // pārbaude vai ir izvadīti ieraksti
          while($row = $select_stmt->fetch(PDO::FETCH_ASSOC)){ // select query tiek izvadits kā daudz-dimensiju masīvs un katra kolonna tiek saglabāta mainīgajā - iet cauri katram ierakstam ar 'while' ciklu
      ?>
      <div class="row">
      <div style="border: 4px solid darkorange; float:left; display: inline; border-radius: 5%; margin: auto;" class="col-lg-10">
        <div class="row">
        <br>
        <div class ="col-md-4" style="margin: auto;">
        <h2 style="font-size:  150%;">Title - <?php echo $row['Nosaukums']; ?></h2>
        </div>
        <div class ="col-md-4" style="margin: auto;">
        <p style="font-size:  150%;">
          StartDate - <?php echo $row['SakumaDiena'] ?><br>
          EndDate - <?php echo $row['BeiguDiena'] ?><br>
          Transport - <?php echo $row['Transports'] ?><br>
          Transport company - <?php echo $row['Kompanija'] ?><br>
          Country - <?php echo $row['Valsts'] ?><br>
          City - <?php echo $row['Pilseta'] ?><br>
          Times purchased - <?php
          $cid = $row['ID'];
          $skait_stmt = $db->prepare("SELECT COUNT(*) FROM ieperk as i INNER JOIN celojumi as c ON i.Celojums = c.ID WHERE i.Celojums = :cid;");
          $skait_stmt->bindParam(":cid", $cid);
          $skait_stmt->execute();
          $skaitRow = $skait_stmt->fetch(PDO::FETCH_ASSOC);
          echo $skaitRow['COUNT(*)'];?><br>
        </p>
      </div>
      <div class ="col-md-4" style="margin: auto;">
        <h2 style="font-size:  150%;">Price (for 1 person) - €<?php echo $row['Cena1per']?></h2>
        <a class="btn btn-success" style="margin: auto" href="offer_checkout.php?ID=<?php echo $row['ID'];?>&person=1">Purchase</a>
        <br><br>
      </div>
      </div>
      </div>
    </div>
      <?php
    }
          }else {
          $_SESSION[''] = 'No offers found!';
          header('Location: offers.php');
      }
    }
  }
      ?>
    </div>
    <!-- zemāk - visi izmantotie skripti -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>