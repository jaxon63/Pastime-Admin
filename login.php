
<html>
	<head>
		<title>Administrator services</title>
		<link rel="stylesheet" type="text/css" href="styles.css"/>
	</head>
	<body>
		<div class = "banner">
			<img src = "img/Logo.PNG" id = "logo"/>
			<h1>Pastime Administrator Services</h1>
		</div>
		<hr>
		<form method = "POST">
			<label for = "username">Username: </label>
			<input type = "text" name = "username" id = "username"/><br>
			<label for = "password">Password: </label>
			<input type = "password" name = "password" id = "password"/><br>
			<input type = "submit" id = "Login" value = "Log In"/>
		</form>
		<?php
			if($_POST){
				require "database.php";


				$username=mysqli_real_escape_string($conn, $_POST['username']);
				$password=mysqli_real_escape_string($conn, $_POST['password']);


				$preparedQuery=$conn->prepare("SELECT * FROM admins WHERE username=?");
				$preparedQuery->bind_param("s", $username);
				$preparedQuery->execute();

				$result = $preparedQuery->get_result();

				if(mysqli_num_rows($result)==1){
					$row = $result->fetch_assoc();
					if(password_verify($password, $row["password"]))
					{
						if($row["active"] == 1){
							session_start();
							$_SESSION['auth']=true;
							$_SESSION['id']=$row["id"];
							header('location:addActivity.php');
						} else {
							echo "<p>".$row["username"]." has been suspended, unable to log in with this account.</p>";
						}
					} else {
						echo "<p>Incorrect username or password</p>";
					}
				} else {
					echo "<p>Incorrect username or password</p>";
				}

			}
		?>
	</body>
</html>