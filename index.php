<?php
	/* Labb 1 för kursen Webbutveckling med PHP
	   av Per Vrethammar (av222bn) */
	include_once "src/views/HTMLPage.php";
	include_once 'src/controllers/Authenticator.php';
	include_once 'src/models/User.php';
	
	$auth = 'Authenticator';
	$auth::CheckForLoginInfo();
	$page = 'HTMLPage';
	$page::GetPage();
?>

