<?php 
	require_once "connection.php";  // piesauc connection.php failu
	session_start();// sesijas sākums
	if (isset($_POST['btn_register'])) { // funckija - reģistrācija
		$name = $_POST['txt_name']; // padotā informācija tiek saglabāta mainīgajos
		$surname = $_POST['txt_surname'];
		$email = $_POST['txt_email'];
		$tel = $_POST['txt_tel'];
		$password = $_POST['txt_password'];
		$password2 = $_POST['txt_password2'];
		$role = "client"; // pēc noklusējuma visiem jaunajiem kontiem ir klienta loma
		if ($password != $password2) { // pārbauda vai abas ievadītas paroles sakrīt
			$_SESSION['error'] = "Passwords don't match!";
			header("location: register.php");
		} else {
			try {
				$select_stmt = $db->prepare("SELECT Epasts FROM lietotaji WHERE Epasts = :uemail"); // tiek izveidots SELECT query
				$select_stmt->bindParam(":uemail", $email);
				$select_stmt->execute();
				$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
				if ($row['Epasts'] == $email) { // pārbaude - vai epasts jau eksistē datu bāzē
					$_SESSION['error'] = "Email already exists!";
					header("location: register.php");
				} else if (!isset($errorMsg)) { // pārbaude - vai nav neviens kļūdas ziņojums 
					$insert_stmt = $db->prepare("INSERT INTO lietotaji (Vards, Uzvards, Epasts, Telefona_numurs, Parole, Loma) VALUES (:uname, :usurname, :uemail, :utel, :upassword, :urole)"); // tiek izveidots INSERT query
					$insert_stmt->bindParam(":uname", $name);
					$insert_stmt->bindParam(":usurname", $surname);
					$insert_stmt->bindParam(":uemail", $email);
					$insert_stmt->bindParam(":utel", $tel);
					$hashedPW = password_hash($password, PASSWORD_DEFAULT);
					$insert_stmt->bindParam(":upassword", $hashedPW);
					$insert_stmt->bindParam(":urole", $role);
					if ($insert_stmt->execute()) { // pārbaude - vai query izpildījās
						$_SESSION['success'] = "Registration successful!";
						header("location: index.php");
					}
				}
			} catch (PDOException $e) {
				$e->getMessage();
			}
		}
	}
?>