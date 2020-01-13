<?php
	session_start();
	if(!$_SESSION['auth']){
		header('location:login.php');
	}


?>

<html>
	<head>
		<title>Users</title>
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
	<div>
		<h2>Search Users</h2>
		<form method = "POST">
			<label for = "search">Search: (type '*' for all users)</label>
			<input type = "text" name = "search" id = "search" /><br>
			<input type = "submit" class = "submit" name = "submitSearch" value = "Search"/>
		</form>
	</div>

	<?php
		if($_POST){
			require "database.php";
			$search=mysqli_real_escape_string($conn, $_POST["search"]);
			if(empty($search)){
				echo "Please enter search term or '*' for all";
			} else {
				$preparedQuery;
				if($search == '*'){
					$preparedQuery=$conn->prepare("SELECT * FROM Users");
				} else {
					$preparedQuery=$conn->prepare("SELECT * FROM Users WHERE username LIKE ?");
					$search = "%".$search."%";
					$preparedQuery->bind_param("s", $search);
				}
				$preparedQuery->execute();
				$result = $preparedQuery->get_result();

				//Table stuff goes here
				echo "<table><tr><th>ID</th><th>Username</th><th>Email</th><th>Email Confirmed</th><th>Active</th><th>Suspend User</th></tr>";
				while($row = $result->fetch_assoc()){
					$suspended = "";
					$verified = "";
					$active = "";

					if($row["active"] == 0){
						$suspended = "Re-Activate";
						$active = "Suspended";
					} else {
						$suspended = "Suspend";
						$active = "Active";
					}

					if($row["confirmed"] == 0){
						$verified = "Not verified";
					} else {
						$verified = "Verified";
					}

					echo "<tr><td>".$row["id"]."</td><td>".$row["username"]."</td><td>".$row["email"]."</td><td>".$verified."</td><td>".$active."</td><td><form action='suspend.php?id=".$row["id"]."' method='post'><input type='hidden' name = 'id' value='".$row["id"]."'/><input type='submit' value='".$suspended."' onclick= \"return confirm('Are you sure?');\"/></form></td></tr>";
				}
				echo "</table>";
				$preparedQuery->close();

			}

			$conn->close();
		}
	?>

	<br/>
	</body>
</html>