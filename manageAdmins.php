<?php
	session_start();
	if(!$_SESSION['auth']){
		header('location:login.php');
	}

?>

<html>
<head>
	<title>Manage Admins</title>
	<link rel="stylesheet" type = "text/css" href="styles.css"/>
</head>
<body>
	<div class = "banner">
		<img src = "img/Logo.PNG" id = "logo"/>
		<h1>Pastime Administrator Services</h1>
	</div>
	<ul id = "links">
		<li><a class = "link" href = "addActivity.php">Activities</a></li>
		<li><a class = "link" href = "users.php">Users</a></li>
		<li><a class = "link" href = "changePassword.php">Change Password</a></li>
		<li><a class = "link" href = "manageAdmins.php">Manage Admins</a></li>
		<li><a class = "link" href="logout.php">Log Out</a></li>
		<!--<a href = "events.html">Events</a>
		<a href = "admins.html">Admins</a>-->
	</ul>
	<h2>Add Admin</h2>
	<form method  = "post" class = "form">
		<label for = "username">Username: </label>
		<input type = "text" name = "username" id = "username"/><br>
		<label for = "email">Email: </label>
		<input type = "text" name = "email" id = "email"/><br>
		<label for = "password">Password: </label>
		<input type = "password" name = "password" id = "password"/><br>
		<label for = "confirmPassword">Confirm Password: </label>
		<input type = "password" name = "confirmPassword" id = "confirmPassword"/><br>
		<input type = "submit" class = "submit" value = "Add Admin"/>
	</form>

	<?php
		require "database.php";
		$preparedQuery = $conn->prepare("SELECT * FROM Admins");
		$preparedQuery->execute();
		$result = $preparedQuery->get_result();

		echo "<table><tr><th>ID</th><th>Username</th><th>Email</th><th>Suspend</th><th>Delete</th></tr>";
		while($row = $result->fetch_assoc()){
			$isSuspended = "";
			if($row["active"] == 0){
				$isSuspended = "Re-activate";
			} else {
				$isSuspended = "Suspend";
			}

			echo "<tr><td>".$row["id"]."</td><td>".$row["username"]."</td><td>".$row["email"]."</td>";
			if($_SESSION["id"] == $row["id"]){
				echo "<td/><td/></tr>";
			} else {
				echo "<td><form method = 'post' action = 'suspendAdmin.php'><input type = \"hidden\" name = \"id\" value= \"".$row["id"]."\"/><input type = \"submit\" value=\"".$isSuspended."\"/></form></td><td><form method = 'post' action = 'deleteAdmin.php'> <input type = \"hidden\" name = \"id\" value = \"".$row["id"]."\"/><input type = \"submit\" value = \"Delete\" onclick= \"return confirm('Are you sure you want to delete this admin?');\"/></form></td></tr>";
			}
		}

		echo "</table>";

		if($_POST){
			

			$username = $_POST["username"];
			$email = $_POST["email"];
			$password = $_POST["password"];
			$confirmPassword = $_POST["confirmPassword"];

			if($password != $confirmPassword){
				echo "<p>Password does not match Confirm Password</p>";
			} else {
				require "database.php";
				$preparedQuery = $conn->prepare("SELECT * FROM Admins WHERE email = ? OR username = ?");
				$preparedQuery->bind_param("ss", $email, $username);
				$preparedQuery->execute();
				$result = $preparedQuery->get_result();

				if(mysqli_num_rows($result) != 0){
					echo "<p>Username or Email already exists</p>";
				} else {
					$preparedQuery = $conn->prepare("INSERT INTO Admins (username, email, password, active) VALUES (?, ?, ?, 1)");
					$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
					$preparedQuery->bind_param("sss", $username, $email, $hashedPassword);
					if($preparedQuery->execute()){
						header("location:manageAdmins.php");
					} else {
						echo "<p>Could not add admin</p>";
					}

				}
				$preparedQuery->close();
				$conn->close();

			}
		}
	?>

</body>
</html>
