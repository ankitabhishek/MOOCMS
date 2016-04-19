<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['faculty_id']))
{
	require ('login_tools.php');
	load();
}

//PAGE TITLE
$page_title = 'Add lecture | MOOCMS Faculty';

//PAGE HEADER

//get ID of ITEM
if(isset($_GET['id'])){
	$course_id = $_GET['id'];
}else{
	$course_id = 0;
}

//retrive FACULTY PROFILE data from SESSION
$faculty_id = $_SESSION['faculty_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

require ('../../moocms_connect.php');

//giving redirect loop
/*
//check if course belong to faculty
$q1 = "SELECT faculty_id FROM instructor WHERE course_id = '$course_id'";
$r1 = mysqli_query($dbc, $q1);
$instructor_row = mysqli_fetch_array($r1, MYSQLI_ASSOC);

if($instructor_row['faculty_id'] != $faculty_id)
{
	header("Location: manage_course.php");
} 
*/

if($_SERVER['REQUEST_METHOD']=='POST')
{
	require ('../../moocms_connect.php');
	$errors = array();
	
	$course_id = mysqli_real_escape_string($dbc, trim($_POST['course_id']));
	
	//check whether COURSE NAME is EMPTY
	if(empty($_POST['lecture_name']))
	{
		$errors[] = 'Enter lecture name.';
	}else{
		$lecture_name = mysqli_real_escape_string($dbc, trim($_POST['lecture_name']));
	}
	
	//check whether COURSE NAME is EMPTY
	if(empty($_POST['lecture_video']))
	{
		$errors[] = 'Enter youtube video link.';
	}else{
		$lecture_video = mysqli_real_escape_string($dbc, trim($_POST['lecture_video']));
	}

	//insert DATA into DATABASE
	if(empty($errors))
	{
		$x = "INSERT INTO lecture (course_id, lecture_name, lecture_video, lecture_date) VALUES ('$course_id', '$lecture_name', '$lecture_video', NOW())";
		$r1 = mysqli_query($dbc, $x);
		
		//confirm REGISTRATION
		if($r1)
		{
			echo '<h1>Lecture Added Succesfully</h1>';
			echo '<a href = "manage_course.php?id=' . $course_id . '"><<< &nbsp;Back</a>';
			
			//BOTTOM MENU
			echo '<p>
				  <a href="index.php">Home</a> |
				  <a href="instructing_courses.php">Instructing Courses</a> |
				  <a href="settings.php">Settings</a> |
				  <a href="logout.php">Logout</a>
				  </p>';
		}
		
		mysqli_close($dbc);
		
		exit();
	}
	
	//display ERROR(S) if registration FAILS
	else{
		echo '<h1>Error!</h1>
			  <p id="err_msg">The following error(s) occured:<br>';
			  
			  foreach ($errors as $msg)
			  {
				echo " - $msg<br>";
			  }
			  
			  echo 'Please try again</p>';
			  
		mysqli_close($dbc);
	}
	
}

//REGISTRATION FORM
echo '<h1>Add lecture</h1>';
echo '<form action="add_lecture.php" method="POST">';
echo '<input type = "hidden" name = "course_id" value = "' . $course_id . '">';
echo '<p>';
echo 'Lecture Name: <input type="text" name="lecture_name" value="'; if(isset($_POST['lecture_name'])) echo $_POST['lecture_name']; echo'"/>';
echo '</p>';
echo '<p>';
echo 'Youtube Video Key: <input type="text" name="lecture_video" value="'; if(isset($_POST['lecture_video'])) echo $_POST['lecture_video']; echo'"/>';
echo '</p>';
echo '<p>';
echo '<input type="submit" value="Add Lecture">';
echo '</p>';
echo '</form>';


//BOTTOM MENU
echo '<p>
	  <a href="index.php">Home</a> |
	  <a href="instructing_courses.php">Instructing Courses</a> |
	  <a href="settings.php">Settings</a> |
	  <a href="logout.php">Logout</a>
	  </p>';

//PAGE FOOTER

?>