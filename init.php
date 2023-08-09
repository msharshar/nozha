<?php
    session_start();
	
	include "db.php";
	include "inc/lang.php";
	include "inc/functions.php";

	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}

    account();
    friends();

	include "inc/header.php"; 
	include "inc/navbar.php";
	echo "<br><br>";
?>