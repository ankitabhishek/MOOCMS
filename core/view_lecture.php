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
	$lecture_id = $_GET['id'];
}else{
	$lecture_id = 0;
}

$page_title = "View Lecture | MOOCMS";

//PAGE HEADER

//retrive STUDENT PROFILE data from SESSION
$student_id = $_SESSION['student_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

require ('../moocms_connect.php');

$q1 = "SELECT course_id, lecture_name, lecture_video, lecture_date FROM lecture WHERE lecture_id = '$lecture_id'";
$r1 = mysqli_query($dbc, $q1);
$lecture_row = mysqli_fetch_array($r1, MYSQLI_ASSOC);

$course_id = $lecture_row['course_id'];

$q2 = "SELECT course_name FROM course WHERE course_id = '$course_id'";
$r2 = mysqli_query($dbc, $q2);
$course_row = mysqli_fetch_array($r2, MYSQLI_ASSOC);

$course_name = $course_row['course_name'];

echo "<h1>" . $course_name . "</h1>";
echo "<h3>" . $lecture_row['lecture_name'] . "</h3>";
echo $lecture_row['lecture_date'] . "<br><br>";

echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $lecture_row['lecture_video'] . '" frameborder="0" allowfullscreen></iframe>';

echo "<br><br>";
echo "<a href='course_content.php?id={$course_id}'><<< &nbsp;Back</a>";
echo "<br>";

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