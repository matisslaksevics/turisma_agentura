<?php
  session_start();// sesijas sākums
  require_once "connection.php";// piesauc connection.php failu
   if($_SESSION['account'] != "admin") { // pārbaude - vai lietotājs ir administrators
    $_SESSION['error'] = "You don't have the permission to this page!"; // kļūdas ziņojums
    header("location: home.php"); // lietotājs tiek pārvests uz home.php lapu
  }
  if(!isset($_SESSION['account'])) {  // pārbaude - vai sesija ir aktīva
      $_SESSION['error'] = "Please login to access the website!";
      header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
    }
  $select_stmt = $db->prepare("SELECT * FROM lietotaji");
  $select_stmt->execute();
  if (isset($_GET['ID'])) { // funkcija - tiek izdzēsts lietotājs ar ID kas tiek padots 
    $user_id = $_GET['ID'];
    if($user_id == $_SESSION['id_user']){ // pārbaude - vai padotais ID nav tāds pats kā aktīvā lietotāja ID
      $_SESSION['error'] = "You cannot delete yourself!";
      header("location: account_panel.php"); // lietotājs tiek pārvests uz account_panel.php lapu
      exit();
    }
    $delete_stmt = $db->prepare("DELETE FROM lietotaji WHERE ID = :id"); // tiek izveidots DELETE query
    $delete_stmt->bindParam(":id", $user_id);
    $result = $delete_stmt->execute();
    if ($result == TRUE) { // pārbauda vai query ir izpildījies
        $_SESSION['error'] = 'Account deleted successfully';
        header('Location: account_panel.php');
        exit();
    }
}
?>
<html> <!-- html kods zemāk -->
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
            <div class="dropdown-menu" aria-labelledby="navbarDropdown"> <!-- drop-down izvelne priekš administratora paneliem -->
              <a class="dropdown-item" href="admin_offers.php">Offer manager</a>
              <a class="dropdown-item" href="account_panel.php">Account manager</a>
            </div>
          </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
        <a class="nav-link" style="color: blue;" href="profile.php"><?php echo $_SESSION['first_name'];?></a> <!-- funkcija - izvada lietotāja vārdu kā hyperlink uz profila lapu -->
        <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php">Logout</a> <!-- poga kurai ir logout funkcija -->
      </form>
      </div>
    </nav>
    <div class="container">
    <h2>Registered accounts</h2>
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
    <table class="table" style="width: 100%; color: white;"> <!-- izveidota tabula kurā tiks izvadīts saraksts ar reģistrētajiem kontiem -->
      <tbody>
        <thead>
          <tr>
            <th>ID</th> <!-- pievienoti tabulas kolonnu nosaukumi -->
            <th>Name</th>
            <th>Surname</th>
            <th>E-mail</th>
            <th>Phone number</th>
            <th>Role</th>
            <th colspan="2">Actions</th>
          </tr>  
        </thead>
        <?php
          while($row = $select_stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
        <tr>
          <td><?php echo $row['ID']; ?></td> <!-- tiek izvadīta visu kontu informācija tabulā -->
          <td><?php echo $row['Vards']; ?></td>
          <td><?php echo $row['Uzvards']; ?></td>
          <td><?php echo $row['Epasts']; ?></td>
          <td><?php echo $row['Telefona_numurs']; ?></td>
          <td><?php echo $row['Loma']; ?></td>
          <td><a class="btn btn-info" href="account_edit.php?ID=<?php echo $row['ID']; ?>">Edit</a>&nbsp;<a class="btn btn-danger" onclick="checker()" href="account_panel.php?ID=<?php echo $row['ID']; ?>">Delete</a></td> <!-- katrai rindai izveidojas 2 pogas - viena ar delete funkciju un otra ar edit funkciju -->
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