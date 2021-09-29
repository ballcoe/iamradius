
<?php
	session_start();
	include("config.php");
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$conn -> set_charset("utf8"); 
	if(isset($_SESSION["UserID"])){
		$sqladdr='update operator set address="0" WHERE id = "'.$_SESSION["UserID"].'"';	
		$conn->query($sqladdr);
		$conn->closel;
		unset($_SESSION["UserID"]);
	}
	if(isset($_SESSION["User"])){
		unset($_SESSION["User"]);
	}
	echo "<script>";
	echo "window.location='index';"; 
	echo "</script>";
?>