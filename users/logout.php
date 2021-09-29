
<?php
	session_start();
	include("../iamradius/config.php");
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$conn -> set_charset("utf8"); 
	if(isset($_SESSION["UserIDPortal"])){
		$sqladdr='update userinfo set address="0" WHERE id = "'.$_SESSION["UserIDPortal"].'"';	
		$conn->query($sqladdr);
		$conn->closel;
		unset($_SESSION["UserIDPortal"]);
	}
	if(isset($_SESSION["UserPortal"])){
		unset($_SESSION["UserPortal"]);
	}
	echo "<script>";
	echo "window.location='./';"; 
	echo "</script>";
?>