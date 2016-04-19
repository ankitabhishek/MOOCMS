<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['student_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "MOOCMS | Home";

//PAGE HEADER


//fixing the "No name change on front page after profile update" BUG
//retrive PROFILE data from DATABASE

$id = $_SESSION['student_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];


echo "<h1>HOME</h1>
	  <p>Hello, {$first_name} {$last_name}</p>";


//BOTTOM MENU
echo '<p>
	  <a href="index.php">Home</a> |
	  <a href="my_courses.php">My Courses</a> |
	  <a href="browse_courses.php">Browse Courses</a> |
	  <a href="settings.php">Settings</a> |
	  <a href="logout.php">Logout</a>
	  </p>';

//PAGE FOOTER


?>