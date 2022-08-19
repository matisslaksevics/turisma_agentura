<?php 
  session_start();// sesijas sākums
  if(!isset($_SESSION['account'])) { // pārbaude - vai sesija ir aktīva
    if($_SESSION['account'] != "client") { // pārbaude - vai lietotājs ir klients
      $_SESSION['error'] = "Please login to access the website!"; // kļūdas ziņojums
      header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
    }
    $_SESSION['error'] = "Please login to access the website!";
      header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
  }
?>
<html> <!-- html kods zemāk -->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Home Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
          <li class="nav-item active">
            <?php if ($_SESSION['account'] == "client"): ?> <!-- funkcija - kas pārbauda vai lietotājs ir klients - un izvada pareizo home lapu -->
            <a class="nav-link" href="home.php">Home</a>
            <?php else: ?>
            <a class="nav-link" href="admin_home.php">Home</a>
            <?php endif; ?>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="offers.php">Offers</a>
          </li>
      </ul>
    <form class="form-inline my-2 my-lg-0">
      <a class="nav-link" href="profile.php"><?php echo $_SESSION['first_name'];?></a><!-- funkcija - izvada lietotāja vārdu kā hyperlink uz profila lapu -->
       <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php">Logout</a> <!-- poga kurai ir logout funkcija -->
    </form>
  </div>
</nav>
    <div class="text-center mt-5">
      <div class="container">
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
        <h1>Client Page</h1>
        <hr>
        <h3>
          <?php if (isset($_SESSION['account'])) { ?>
          Welcome, <?php echo $_SESSION['first_name']; }?>
        </h3>
      </div>
    </div>
    <!-- zemāk - visi izmantotie skripti -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
