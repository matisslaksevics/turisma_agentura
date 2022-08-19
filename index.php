<?php 
  session_start(); // sesijas sākums
  if(isset($_SESSION['account'])) { // pārbaude - vai sesija ir aktīva
    if($_SESSION['account'] != "admin") { // pārbaude - vai lietotājs ir administrators
      $_SESSION['error'] = "You are already logged in!"; // kļūdas ziņojums
      header("location: home.php"); // lietotājs tiek pārvests uz home.php lapu
    } else {
      $_SESSION['error'] = "You are already logged in!"; // kļūdas ziņojums
      header("location: admin_home.php"); // lietotājs tiek pārvests uz admin_home.php lapu
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<html> <!-- html kods zemāk -->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Authorize</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body class="bg-dark text-white">
    <div class="container">
      <h1>Welcome to our website!</h1>
      <p>Please log in or register to be able to access our services!</p>
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
      <form style="padding-top: 20px; border: 3px solid dimgray; background-color: #2b2b2b;" action="login_db.php" method="POST"> <!-- tiek izveidota forma priekš autorizācijas -->
        <div class="form-group col-3">
          <div class="validate">
            <label for="Tooltips" class="error"></label>
            <label for="email">Email address</label>
            <input type="email" class="form-control" name="txt_email" placeholder="Enter email" required>
            <label for="password" style="padding-top: 20px;">Password</label>
            <input type="password" class="form-control" name="txt_password" placeholder="Enter password" required>
            <small id="passwordHelp" class="form-text text-muted"><a href="pwrecovery.php">Forgot your password?</a></small>
          </div>
        </div>
        <div class="myDiv col-3">
          <button type="submit" class="btn btn-primary bg-success border-success" name="btn_login">Log in</button> <!-- poga kas veic login funkciju -->
          <small id="newAccount" class="form-text text-muted"><a href="register.php">Dont have an account? Come register!</a></small> <!-- hyperlinks kas pārved lietotāju uz reģistrācijas lapu -->
        </div>
      </form>
    </div> <!-- zemāk - visi izmantotie skripti -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
  </body>
</html>