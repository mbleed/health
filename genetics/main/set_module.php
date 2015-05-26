<?php
	include "./classes/ez_sql.php"; //include db class
	
	session_name('gedSession');
	session_start();
	
	$_SESSION['mod'] = $_GET['mod'];
	
	header("Location: ./mod_index.php");
?>