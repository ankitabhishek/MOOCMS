<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['admin_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "Logout";

//PAGE HEADER


$_SESSION = array();
session_destroy();

//DISPLAY log out CONFIRMATION
echo '<h1>Goodbye!</h1>
	  <p>You are now logged out.</p>
	  <p><a href="login.php">LOGIN</a></p>';

?>


<?php
//PAGE FOOTER

?>