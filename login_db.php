<?php 
	require_once 'connection.php'; // piesauc connection.php failu
	session_start(); // sesijas sākums
	if (isset($_POST['btn_login'])) { // funkcija - autorizācija
		$email = $_POST['txt_email']; // login informācija tiek saglabāta mainīgajos
		$password = $_POST['txt_password'];
		if ($email AND $password) { // pārbauda vai mainīgie eksistē
			try {
				$select_stmt = $db->prepare("SELECT ID, Vards, Epasts, Parole, Loma FROM lietotaji WHERE Epasts = :uemail"); // tiek izveidots SELECT query
				$select_stmt->bindParam(":uemail", $email);
				$select_stmt->execute();
				while($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) { // aizpilda mainīgos ar ieraksta informāciju
					$dbuserid = $row['ID'];
					$dbemail = $row['Epasts'];
					$dbpassword = $row['Parole'];
					$dbrole = $row['Loma'];
					$dbfirstname = $row['Vards'];
				}
				if ($email != null AND $password != null) { // pārbauda vai mainīgie nav tukši
					if ($select_stmt->rowCount() > 0) { // pārbauda vai ir izvadīti ieraksti no SELECT query
						if ($email == $dbemail AND password_verify($password, $dbpassword) == true) { // pārbauda vai parole atbilst šifrētajai parolei kas ir datu bāzē
							switch($dbrole) { // tiks sastādīts case balstoties uz konta lomu
								case 'admin': // tiek izmantoti SESSION mainīgie 
									$_SESSION['account'] = "admin";
									$_SESSION['first_name'] = $dbfirstname;
									$_SESSION['id_user'] = $dbuserid;
									$_SESSION['success'] = "Admin successfully logged in!";
									header("location: admin_home.php"); // autorizācija veiksmīga
								break;
								case 'client':
									$_SESSION['account'] = "client";
									$_SESSION['first_name'] = $dbfirstname;
									$_SESSION['id_user'] = $dbuserid;
									$_SESSION['success'] = "Client successfully logged in!";
									header("location: home.php");
								break;
								default: // ja neviens case netiek lietots - tad pēc noklusējuma tiek izvadīts kļūdas ziņojums
									$_SESSION['error'] = "Wrong email or password!";
									header("location: index.php");
							}
						} else {
							$_SESSION['error'] = "Wrong email or password!";
								header("location: index.php");
						}
					} else {
						$_SESSION['error'] = "Account doesnt exist!";
						header("location: index.php");
					}
				}
			} catch(PDOException $e) {
				$e->getMessage();
			}
		}
	}
?>