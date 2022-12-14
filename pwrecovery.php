<?php 
  session_start();
  if(isset($_SESSION['account'])) {
    if($_SESSION['account'] != "admin") {
      $_SESSION['error'] = "You are already logged in!";
      header("location: home.php");
    } else {
      $_SESSION['error'] = "You are already logged in!";
      header("location: admin_home.php");
    }
  }
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Password recovery</title>
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body class="bg-dark text-white">
    <div class="container">
      <h1>Welcome to password recovery!</h1>
      <p>Please enter your email bellow so we can send a recovery link to you!</p>
      <form style="padding-top: 20px; border: 3px solid dimgray; background-color: #2b2b2b;">
        <div class="form-group col-3">
          <label for="InputEmail3">Email address</label>
          <input type="email" class="form-control" id="InputEmail3" placeholder="Enter email">
        </div>
        <div class="myDiv col-3">
          <button type="submit" class="btn btn-primary bg-success border-success">Send</button>
        </div>
      </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>