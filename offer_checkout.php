<?php
  require_once "connection.php";// piesauc connection.php failu
  require_once('stripe-php/init.php'); // piesauc init.php failu
  session_start(); // sesijas sākums
  if(!isset($_SESSION['account'])) { // pārbaude - vai sesija ir aktīva
    $_SESSION['error'] = "Please login to access the website!"; // kļūdas ziņojums
    header("location: index.php"); // lietotājs tiek pārvests uz index.php lapu
  }
  if(isset($_GET['ID'])) { // funkcija - ceļojuma informācijas izvade pēc padotā ID
    $offer_id = $_GET['ID']; // padotais ID tiek saglabāts mainīgajā
    $select_stmt = $db->prepare("SELECT * FROM celojumi WHERE ID = :id"); // tiek sastādīts SELECT query
    $select_stmt->bindParam(":id", $offer_id);
    $select_stmt->execute();
    if ($select_stmt->rowCount() > 0) { // pārbaude - vai ir izvadīti saraksti
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
  if (isset($_GET['person'])){ // funkcija - personu skaita iegūšana
    $person = (int)$_GET['person']; // mainīgajā saglabā ievadīto personu skaitu INT datu tipā
  }
      \Stripe\Stripe::setApiKey('sk_test_51LBLJLKl3gXSiFYH3o08r4vSlL2XfVyqJeg2oyAeNPSBE4sjFEWtAbG2ThQ4aFCOlivxzQPsZe1WuNgK9skt48cM00TEvRNIlJ'); // tiek noteikta Stipe API atslēga
?>
<html> <!-- html kods zemāk -->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://js.stripe.com/v3/"></script>
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
      <form method="POST" id="checkout" enctype="multipart/form-data"> <!-- tiek izveidota forma priekš ceļojuma informācijas izvadīšanas -->
      <div class="row">
        <div class="col-sm-2">
          <br>
          Title<br>
          Start Date<br>
          End date<br>
          Transport type<br>
          Transport company<br>
          Country<br>
          City<br>
          Person count<br><br><br>
          Price<br>
        </div>
          <div class="col-lg-6">
          <br>
          <?php echo $nosaukums;?><br>
          <?php echo $sakuma_diena;?><br>
          <?php echo $beigu_diena;?><br>
          <?php echo $transports;?><br>
          <?php echo $kompanija;?><br>
          <?php echo $valsts;?><br>
          <?php echo $pilseta;?><br>
          <input type="number" name="person" id="person"/><br> <!-- ievade priekš personu skaita -->
          <input name="update" value="Update" type="submit" class="btn btn-primary"><br> <!-- funkcija - poga kas padod izvelētēto personu skaitu -->
          <?php if(isset($_POST['update'])){
            $person = $_POST['person'];
            header('Location: offer_checkout.php?ID=' . $id . '&person=' . $person);
          }
          ?>
           <?php $fullprice = $cena1per * $person;?> <!-- funkcija - tiek izvadīta cenu kuru aprēķina sareizinot cenu1per ar personu skaitu -->
          <span id="euros" class="amount"><?php echo $fullprice;?> €</span><br>
          <?php
          $newprice = str_replace(",","",$fullprice) * 100; // tiek saglabāta cena ar jaunu formātu priekš Stripe API
          $session = \Stripe\Checkout\Session::create([ // tiek izveidota jauna Stripe API sessija
            // tiek padota specifiskā informācija par pirkumu uz apmaksas funkciju
            'line_items' => [[
              'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                  'name' => 'Celojums',
                ],
                'unit_amount' => $newprice,
              ],
              'quantity' => 1,
            ]],
          'mode' => 'payment',
          // ir 2 linki - viens priekš veiksmīga pirkuma, viens priekš neveiksmīga pirkuma
          'success_url' => 'http://localhost/d41-MatissLaksevics-TurismaAgentura/offer_success.php?ID=' . $_GET['ID'] . '&price=' .  $fullprice,
          'cancel_url' => 'http://localhost/d41-MatissLaksevics-TurismaAgentura/offers.php',
          ]);
          ?>
        </div>
      </div>
    </form>
    <div class="col-sm-2">
    <input value="Checkout" id="checkout-button" type="submit" name="purchase" class="btn btn-primary form-control"> <!-- poga kura izpilda norēķināšanās funkciju --> 
    <!-- zemāk - skripts kas izveido norēķināšanās sessiju -->
    <script>
      var stripe = Stripe('pk_test_51LBLJLKl3gXSiFYHOZmEdoR4FXIWunoUTVgjRRzxLewLV5NUiPPESUaGAox1GFtxESQ4lbmKZqoFZEv6cJBvJdlu00ieWnJDeC');
      const btn = document.getElementById("checkout-button");
      btn.addEventListener('click', function(e){
        e.preventDefault();
        stripe.redirectToCheckout({
          sessionId: "<?php echo $session->id;?>"
        });
      });
    </script>
    <a class="btn btn-outline-danger" style="margin: auto" href="offers.php">Cancel</a>
  </div>
    </div>
    <!-- zemāk - visi izmantotie skripti -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- zemāk - skripts, kas parāda ziņojumu ar pieprasījumu lietotājam vai vēlas turpināt ar izmaiņām -->
    <script>
     function checker(){
        var result = confirm('Do you wish to proceed with your purchase?');
        if (result == false) {
          event.preventDefault();
        }
      }
    </script> 
  </body>
</html>