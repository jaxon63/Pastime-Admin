<?php
	session_start();
	if(!$_SESSION['auth']){
		header('location:login.php');
	}

	if($_POST){
		require "database.php";

		$name = mysqli_real_escape_string($conn, $_POST['name']);
		$equipment = mysqli_real_escape_string($conn, $_POST['equipment']);
		if(empty($name) || empty($equipment) || $_FILES["icon"]["error"] != 0){
			echo "Please fill in all fields";
		} else {

			$preparedQuery = $conn->prepare("SELECT * FROM Activities WHERE name = ?");
			$preparedQuery->bind_param("s", $name);
			$preparedQuery->execute();

			$result = $preparedQuery->get_result();
			if(mysqli_num_rows($result) == 0){
				$directory = "iconImages/";
				$targetFile = $directory . basename($_FILES["icon"]["name"]);
				$uploadOk = true;
				$imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
				$checkIfImage = getimagesize($_FILES["icon"]["tmp_name"]);

				if(!$checkIfImage){
					echo "File is not an image";
					$uploadOk = false;
				}

				if(file_exists($targetFile)){
					echo "File already exists";
					$uploadOk = false;
				}

				if($_FILES["icon"]["size"] > 1000000){
					echo "File is too large";
					$uploadOk = false;
				}

				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
					echo "Only jpg, jpeg or png files";
					$uploadOk = false;
				}

				if($uploadOk){
					if(move_uploaded_file($_FILES["icon"]["tmp_name"], $targetFile)){
						$preparedQuery = $conn->prepare("INSERT INTO activities(name, icon, equipment) VALUES (?, ?, ?)");
						$preparedQuery->bind_param("sss", $name, $targetFile, $equipment);
						if($preparedQuery->execute()){
							echo "Activity was successfully added";
						} else {
							echo "There was an error adding the activity";
						}
						$preparedQuery->close();

					} else {
						echo "There was an error uploading the file";
					}
				}
			} else {
				echo "Activity already exists";
			}

			$conn->close();
		}
	}
?>

<html>
<head>
	<title>Add Activity</title>
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
	<h2>Add an Activity</h2>
	<div id = "form">
		<form  method = "POST"  enctype = "multipart/form-data">
			<label for = "name">Name of Activity: </label>
			<input type = "text" name="name" id = "name"/> <br>
			<label for = "icon">Icon: </label>
			<input type = "file" name = "icon" id = "icon" accept = "image/png, image/jpeg"/> <br>
			<div id = "Equipment">
				<label for = "equipment">Equipment: </label>
				<textarea name = "equipment" id = "equipment"></textarea>
				<p id = "textareaHelp">(Separate each equipment item with a ',')</p><br>
			</div>
			<!--<input type = "text" name = "equipment"/>-->
			<input type = "submit" class = "submit" value = "Submit"/>
		</form>
	</div>

	</br>

	<?php
	require "database.php";
	$preparedQuery = $conn->prepare("SELECT * FROM Activities");
	$preparedQuery->execute();
	$result = $preparedQuery->get_result();

	echo "<table><tr><th>ID</th><th>Icon</th><th>Name</th><th>Equipment</th><th>Delete?</th></tr>";
	while($row = $result->fetch_assoc()){
		echo "<tr><td>".$row["id"]."</td><td><img src='".$row["icon"]."' width='100' height='100'/></td><td>".$row["name"]."</td><td>".$row["equipment"]."</td><td><form action = 'delete.php?id=".$row["id"]."' method='post'><input type='hidden' name='id' value='".$row["id"]."'/><input type='submit' onclick=\"return confirm('Are you sure you want to delete this activity?');\" value='Delete'/></form></td></tr>";
	}
	echo "</table>";
	?>
	

</body>
</html>
