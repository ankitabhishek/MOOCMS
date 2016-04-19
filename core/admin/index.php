<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['admin_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "MOOCMS | Home";

//PAGE HEADER


//fixing the "No name change on front page after profile update" BUG
//retrive PROFILE data from DATABASE

$id = $_SESSION['admin_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];


echo "<h1>HOME</h1>
	  <p>Hello, {$first_name} {$last_name}</p>";


//BOTTOM MENU
echo '<p>
	  <a href="index.php">Home</a> |
	  <a href="manage_students.php">Manage Students</a> |
	  <a href="manage_faculties.php">Manage Faculties</a> |
	  <a href="manage_courses.php">Manage Courses</a> |
	  <a href="logout.php">Logout</a>
	  </p>';

//PAGE FOOTER


?>