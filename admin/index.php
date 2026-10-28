<?php
	include "../config/database.php";
	session_start();
	if(!isset($_SESSION["USERNAME"])){
		session_destroy();
 
		header("location:../index.php");
		exit();
	}
	echo "Welcome ".$_SESSION["USERNAME"]."!";
?>