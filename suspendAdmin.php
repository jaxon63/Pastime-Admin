<?php
	if(isset($_POST["id"])){
		require "database.php";

		$id = $_POST["id"];
		$preparedQuery = $conn->prepare("SELECT * FROM Admins WHERE id = ?");
		$preparedQuery->bind_param("i", $id);
		$preparedQuery->execute();
		$result = $preparedQuery->get_result();

		$isActive = $result->fetch_assoc()["active"];
		if($isActive == 1){
			$isActive = 0;
		} else {
			$isActive = 1;
		}

		$preparedQuery = $conn->prepare("UPDATE Admins SET active = ? WHERE id = ?");
		$preparedQuery->bind_param("ii", $isActive, $id);
		$preparedQuery->execute();

		$preparedQuery->close();
		$conn->close();
	}
	header("location: manageAdmins.php");

?>