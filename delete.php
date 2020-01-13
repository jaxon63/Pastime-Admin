<?php

	$id = $_POST['id'];
	

	require "database.php";
	$preparedQuery = $conn->prepare("SELECT * FROM Activities WHERE id=?");
	$preparedQuery->bind_param("i", $id);
	$preparedQuery->execute();

	$results = $preparedQuery->get_result();
	$row = $results->fetch_assoc();
	$iconImage = $row["icon"];

	unlink($iconImage);

	
	$preparedQuery = $conn->prepare("DELETE FROM Activities WHERE id=?");
	$preparedQuery->bind_param("i", $id);
	$preparedQuery->execute();

	
	$preparedQuery->close();
	$conn->close();
	
	header("location:addActivity.php");


?>