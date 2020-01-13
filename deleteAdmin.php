<?php
	if(isset($_POST["id"])){
		require "database.php";

		$id = $_POST["id"];
		$preparedQuery = $conn->prepare("SELECT * FROM Admins");
		$preparedQuery->execute();
		$result = $preparedQuery->get_result();

		if(mysqli_num_rows($result) == 1){
			echo "<p>Unable to delete last admin</p><br><a href = \"manageAdmins.php\">Back</a>";
		} else {
			$preparedQuery = $conn->prepare("DELETE FROM Admins WHERE id = ?");
			$preparedQuery->bind_param("i", $id);
			$preparedQuery->execute();
		}
		
		$preparedQuery->close();
		$conn->close();
	}
	header("location: manageAdmins.php");

?>