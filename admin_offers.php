<?php
  session_start();// sesijas sākums
  require_once "connection.php";// piesauc connection.php failu
   if($_SESSION['account'] != "admin") {// pārbaude - vai lietotājs ir administrators
    $_SESSION['error'] = "You don't have the permission to this page!";// kļūdas ziņojums
    header("location: home.php");// lietotājs tiek pārvests uz home.php lapu
  }
  if(!isset($_SESSION['account'])) {// pārbaude - vai sesija ir aktīva
      $_SESSION['error'] = "Please login to access the website!";
      header("location: index.php");// lietotājs tiek pārvests uz index.php lapu
  // visi mainīgie tiek samainīti uz tukšumiem
  }
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
  if (isset($_GET['ID'])) { // funkcija - tiek izdzēsts ceļojums ar ID kas tiek padots
    $offer_id = $_GET['ID'];
    $delete_stmt = $db->prepare("DELETE FROM celojumi WHERE ID = :id"); // tiek izveidots DELETE query
    $delete_stmt->bindParam(":id", $offer_id);
    $result = $delete_stmt->execute();
    if ($result == TRUE) { // pārbauda vai query ir izpildījies
      $_SESSION['mistake'] = "Record has been deleted!"; 
      header("location: admin_offers.php");
      exit();
    }
  }
  if(isset($_POST['create'])){ // funkcija - jauna ceļojuma izveide
    $nosaukums = $_POST['nosaukums']; // ievadformu aizpildītā informācija tiek saglabāta mainīgajos
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
    } else if($cena1per <= 0) {
      $_SESSION['mistake'] = 'Invalid price!';
      header('Location: admin_offers.php');
      exit();
    } else {
      $insert_stmt = $db->prepare("INSERT INTO celojumi(Nosaukums, SakumaDiena, BeiguDiena, Kompanija, Transports, Valsts, Pilseta, Cena1per) VALUES(:nosaukums, :sakuma_diena, :beigu_diena, :kompanija, :transports, :valsts, :pilseta, :cena1per)"); // tiek izveidots INSERT query
      $insert_stmt->bindParam(":nosaukums", $nosaukums);
      $insert_stmt->bindParam(":sakuma_diena", $sakuma_diena);
      $insert_stmt->bindParam(":beigu_diena", $beigu_diena);
      $insert_stmt->bindParam(":transports", $transports);
      $insert_stmt->bindParam(":kompanija", $kompanija);
      $insert_stmt->bindParam(":valsts", $valsts);
      $insert_stmt->bindParam(":pilseta", $pilseta);
      $insert_stmt->bindParam(":cena1per", $cena1per);
      if($insert_stmt->execute()){ // pārbauda vai query ir izpildījies
        $_SESSION['success'] = 'Record added successfully!';
        header('Location: admin_offers.php');
        exit();
        }
    }
  }
?>
<html><!-- html kods zemāk -->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Offer panel</title>
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
            <a class="nav-link" href="admin_home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="offers.php">Offers<span class="sr-only"></span></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Admin panels
            </a>
            <div class="dropdown-menu active" aria-labelledby="navbarDropdown"> <!-- drop-down izvelne priekš administratora paneliem -->
              <a class="dropdown-item" href="admin_offers.php">Offer manager</a>
              <a class="dropdown-item" href="account_panel.php">Account manager</a>
            </div>
          </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
        <a class="nav-link" href="profile.php"><?php echo $_SESSION['first_name'];?></a> <!-- funkcija - izvada lietotāja vārdu kā hyperlink uz profila lapu -->
        <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php">Logout</a> <!-- poga kurai ir logout funkcija -->
      </form>
      </div>
    </nav>
    <div class="container">
    <h2>Offers panel</h2>
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
      <div id="modalDialog" class="modal col-md-4" style="margin: auto;"> <!-- tiek izveidots modal pop-up logs priekš jauna ceļojuma izveides -->
        <div class="modal-content animate-top bg-dark">
            <div class="modal-header">
                <h5 class="modal-title">New Offer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" id="newOffer" enctype="multipart/form-data"> <!-- tiek izveidota forma priekš jauna ceļojuma izveides -->
            <div class="modal-body">
                <div class="response"></div>
               <input type="hidden" name="offer_id" value="<?php echo $offer_id; ?>">
        <br><label>Title</label> <!-- tiek izveidotas ievades -->
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
            </div>
            <div class="modal-footer">
                <br><input name="create" value="Create Offer" type="submit" class="btn btn-primary form-control"><br> <!-- poga kas izpilda jauna ceļojuma izveides funkciju -->
            </div>
            </form>
        </div>
    </div>
    <button id="mbtn" class="btn btn-outline-success turned-button">New Offer</button>
    <table class="table" style="width: 100%; color: white; margin: auto;"> <!-- izveidota tabula kurā tiks izvadīts saraksts ar ceļojumiem -->
      <tbody>
        <thead>
          <tr>
            <th>ID</th> <!-- pievienoti tabulas kolonnu nosaukumi -->
            <th>Title</th>
            <th>StartDate</th>
            <th>EndDate</th>
            <th>Transport</th>
            <th>Company</th>
            <th>Country</th>
            <th>City</th>
            <th>Price</th>
            <th colspan="2">Actions</th>
          </tr>  
        </thead>
        <?php
        $select_stmt = $db->prepare("SELECT * FROM celojumi");
        $select_stmt->execute();
        while($row = $select_stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
        <tr>
          <td><?php echo $row['ID']; ?></td> <!-- tiek izvadīta visu ceļojumu informācija tabulā -->
          <td><?php echo $row['Nosaukums']; ?></td>
          <td><?php echo $row['SakumaDiena']; ?></td>
          <td><?php echo $row['BeiguDiena']; ?></td>
          <td><?php echo $row['Transports']; ?></td>
          <td><?php echo $row['Kompanija']; ?></td>
          <td><?php echo $row['Valsts']; ?></td>
          <td><?php echo $row['Pilseta']; ?></td>
          <td><?php echo $row['Cena1per']; ?>  €</td>
          <td><a class="btn btn-outline-info" href="offer_form.php?ID=<?php echo $row['ID']; ?>">Edit</a>
            &nbsp;<a class="btn btn-outline-danger" onclick="checker()" href="admin_offers.php?ID=<?php echo $row['ID']; ?>">Delete</a></td> <!-- katrai rindai izveidojas 2 pogas - viena ar delete funkciju un otra ar edit funkciju -->
        </tr>
        <?php 
          endwhile; // tiek apstādināts while cikls
        ?>
      </tbody>
    </table>
    </div> <!-- zemāk - visi izmantotie skripti -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- zemāk - skripts, kas kontrolē modal loga parādīšanu un aizvēršanu -->
    <script>
      var modal = $('#modalDialog');
      var btn = $("#mbtn");
      var span = $(".close");
      $(document).ready(function(){
        btn.on('click', function() {
          modal.show();
        });
        span.on('click', function() {
          modal.hide();
        });
      });
      $('body').bind('click', function(e){
        if($(e.target).hasClass("modal")){
          modal.hide();
        }
      });
</script>
<!-- zemāk - skripts, kas parāda ziņojumu ar pieprasījumu lietotājam vai vēlas turpināt ar izmaiņām -->
<script>
   function checker(){
        var result = confirm('Do you wish to delete this offer?');
        if (result == false) {
          event.preventDefault();
        }
      }
</script>
</body>
</html>