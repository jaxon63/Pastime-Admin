<?php
	session_start();
	if(!$_SESSION['auth']){
		header('location:login.php');
	}

	if($_POST){
		require "database.php";
		$currentPassword = mysqli_real_escape_string($conn, $_POST["currentPassword"]);
		$newPassword = mysqli_real_escape_string($conn, $_POST["newPassword"]);
		$confirmPassword = mysqli_real_escape_string($conn, $_POST["confirmPassword"]);

		$preparedStatement = $conn->prepare("SELECT * FROM admins WHERE id = ?");
		$preparedStatement->bind_param("i", $_SESSION["id"]);
		$preparedStatement->execute();

		$result = $preparedStatement->get_result();

		$row = $result->fetch_assoc();
		if(password_verify($currentPassword, $row["password"])){
			if($newPassword == $confirmPassword){
				if($currentPassword != $newPassword){
					$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
					$preparedStatement = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
					$preparedStatement->bind_param("si", $hashedPassword, $_SESSION["id"]);
					$preparedStatement->execute();
					echo "Password updated";
				} else {
					echo "New password can't be the same as your current password";
				}
			} else {
				echo "New Password doesn't match with Confirm New Password";
			}
		} else {
			echo "Current password was incorrect";
		}
	}
?>


<html>
<head>
	<title>Change Password</title>
	<link rel="stylesheet" type="text/css" href="styles.css"/>
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
	<h2>Change Password</h2>
	<form method = "Post">
		<label for = "currentPassword">Current Password</label>
		<input type = "password" name = "currentPassword" id="currentPassword" /><br>
		<label for = "newPassword">New Password</label>
		<input type = "password" name = "newPassword" id = "newPassword"/><br>
		<label for = "confirmPassword">Confirm New Password</label>
		<input type = "password" name = "confirmPassword" id = "confirmPassword"/><br>
		<input type = "submit" value = "Reset Password"/>
	</form>
</body>

</html>