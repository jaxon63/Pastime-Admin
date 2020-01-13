<?php
	if(isset($_POST["id"])){

		$host="localhost";
		$user="root";
		$pw="";
		$db="test";

		$conn = mysqli_connect($host, $user, $pw, $db);

	} else {
		header("location:addActivity.php");
	}
}

?>