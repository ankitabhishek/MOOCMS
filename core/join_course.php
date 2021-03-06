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

$page_title = "Join Course | MOOCMS";

//PAGE HEADER

//retrive STUDENT PROFILE data from SESSION
$student_id = $_SESSION['student_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

require ('../moocms_connect.php');

$q = "INSERT INTO enrollment (course_id, student_id, reg_date) VALUES ($course_id, $student_id, NOW())";
$r = mysqli_query($dbc, $q);

header("Location: view_course.php?id=$course_id");

?>