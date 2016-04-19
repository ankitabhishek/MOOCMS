<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['student_id']))
{
	require ('login_tools.php');
	load();
}

//get ID of ITEM
if(isset($_GET['id'])){
	$course_id = $_GET['id'];
}else{
	$course_id = 0;
}

$page_title = "View Course | MOOCMS";

//PAGE HEADER

//retrive STUDENT PROFILE data from SESSION
$student_id = $_SESSION['student_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

require ('../moocms_connect.php');

$q_check = "SELECT student_id FROM enrollment WHERE course_id = '$course_id' AND student_id = '$student_id'";
$r_check = mysqli_query($dbc, $q_check);

if(mysqli_num_rows($r_check) != 0)
{
	header("Location: course_content.php?id=$course_id");
}

$q = "SELECT course_id, course_name, start_date FROM course WHERE course_id = '$course_id'";
$r = mysqli_query($dbc, $q);
$course_row = mysqli_fetch_array($r, MYSQLI_ASSOC);

echo "<h1>Course Details</h1>
	  <p>
	  	<b>Course Name: </b>" . $course_row['course_name'] . "<br>
	  	<b>Start Date: </b>" . $course_row['start_date'] . "<br><br>
	  	<a href = 'join_course.php?id={$course_row['course_id']}' >Join Course</a>
	  </p>";


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