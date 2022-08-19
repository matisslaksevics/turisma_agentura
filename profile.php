<?php
  require_once 'connection.php'; // piesauc connection.php failu
  session_start(); // sesijas sākums
   if(!isset($_SESSION['account'])) { // pārbaude - vai sesija ir aktīva
    if(isset($_SESSION['id_user']) != $_GET['ID']) { 
    $_SESSION['error'] = "Please login to access the website!";// kļūdas ziņojums
    header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
  }
  $_SESSION['error'] = "Please login to access the website!";// kļūdas ziņojums
    header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
  }
  if (isset($_POST['update'])) { // funkcija - konta informācijas rediģēšana
    $user_id = $_POST['user_id']; // padotās izmaiņas ievadformās tiek saglabātas mainīgajos
    $vards = $_POST['vards'];
    $uzvards = $_POST['uzvards'];
    $epasts = $_POST['epasts'];
    $telnr = $_POST['telnr'];
    $update_stmt = $db->prepare("UPDATE lietotaji SET Vards = :vards, Uzvards = :uzvards, Epasts = :epasts, Telefona_numurs = :telnr WHERE ID = :id"); // tiek izveidots query priekš informācijas rediģēšanas datu bāzē
    $update_stmt->bindParam(":vards", $vards);
    $update_stmt->bindParam(":uzvards", $uzvards);
    $update_stmt->bindParam(":epasts", $epasts);
    $update_stmt->bindParam(":telnr", $telnr);
    $update_stmt->bindParam(":id", $user_id);
    $result = $update_stmt->execute(); // tiek izpildīts query 
    if ($result == TRUE) { // pārbaude - vai query ir veiksmīgi izpildijies
      $_SESSION['success'] = 'Your information was edited successfully!';
      if ($_SESSION['account'] != "admin"){
        header('Location: home.php');
        exit();
      }else{
        header('Location: admin_home.php');
        exit();
      }
    }
  }
  if (isset($_SESSION['id_user'])) { // funkcija - konta esošās informācijas izvade
    $user_id = $_SESSION['id_user'];
    $select_stmt = $db->prepare("SELECT * FROM lietotaji WHERE ID = :id");
    $select_stmt->bindParam(":id", $user_id);
    $select_stmt->execute();
    if ($select_stmt->rowCount() > 0) { // tiek parbaudits vai ir izvadīti ieraksti
      while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) { // select query tiek izvadits kā daudz-dimensiju masīvs un katra kolonna tiek saglabāta mainīgajā - iet cauri katram ierakstam ar 'while' ciklu
        $id = $row['ID'];
        $vards = $row['Vards'];
        $uzvards = $row['Uzvards'];
        $epasts = $row['Epasts'];
        $telnr = $row['Telefona_numurs'];
      }
    }
  }
?>
<html> <!-- html kods zemāk -->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Account Edit</title>
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
          <a class="nav-link" href="profile.php"><?php echo $_SESSION['first_name'];?></a> <!-- funkcija - izvada lietotāja vārdu kā hyperlink uz profila lapu -->
        <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php">Logout</a> <!-- poga kurai ir logout funkcija -->
      </form>
      </div>
    </nav>
    <div class="container">
      <h2>My account</h2>
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
      <div class="row">
      <div class="col-md-6" style="margin: auto;">
      <form method="POST" enctype="multipart/form-data"> <!-- forma priekš konta informācijas rediģēšanas -->
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> <!-- katrā ievadformā tiek padota informācija no speficiski izvēlētā konta -->
        <br><label>Name</label>
        <br><input type="text" class="form-control" name="vards" placeholder="Enter offer title" required value="<?php echo $vards; ?>">
        <br><label>Surname</label>
        <br><input type="text" class="form-control" name="uzvards" required value="<?php echo $uzvards; ?>">
        <br><label>Email</label>
        <br><input type="text" class="form-control" name="epasts" required value="<?php echo $epasts; ?>">
       <br><label>Phone number</label>
        <br><input type="text" class="form-control" name="telnr" required value="<?php echo $telnr; ?>">
        <div class="form-group">
          <br><input name="update" value="Save" type="submit" class="btn btn-primary form-control" onclick="checker()"> <!-- poga kas izpilda konta rediģēto izmaiņu glabāšanas funkciju -->
        </div>
      </form>
    </div>
    <div class="col-sm-3" style="margin: auto;">
      <a class="btn btn-success" style="margin: auto" href="offers_profile.php?ID=<?php echo $id;?>">Past purchases</a><br><br> <!-- poga kas aizved lietotāju uz iepriekšējo pirkumu sarakstu -->
      <a class="btn btn-warning" style="margin: auto" onclick="checker()" href="profile_deletion.php?ID=<?php echo $id;?>">Delete account</a> <!-- poga kas izdzēš lietotāja kontu -->
    </div>
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