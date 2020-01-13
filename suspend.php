<?php
	$id=$_POST["id"];

	require "database.php";
	
	$preparedQuery = $conn->prepare("SELECT * FROM Users WHERE id = ?");
	$preparedQuery->bind_param("i", $id);
	$preparedQuery->execute();

	$result = $preparedQuery->get_result();

	$row = $result->fetch_assoc();
	$preparedQuery = $conn->prepare("UPDATE Users SET active = ? WHERE id = ?");

	if($row["active"] == 1){
		$preparedQuery->bind_param("ii", $i=0, $id); //i hate php
	} else {
		$preparedQuery->bind_param("ii", $i=1, $id);
	}

	$preparedQuery->execute();

	$preparedQuery->close();
	$conn->close();
	

	header("location:users.php");
?>