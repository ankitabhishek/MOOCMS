<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['faculty_id']))
{
	require ('login_tools.php');
	load();
}

//PAGE TITLE
$page_title = 'Edit lecture | MOOCMS Faculty';

//PAGE HEADER

//get ID of ITEM
if(isset($_GET['id'])){
	$lecture_id = $_GET['id'];
}else{
	$lecture_id = 0;
}

//retrive FACULTY PROFILE data from SESSION
$faculty_id = $_SESSION['faculty_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

if($_SERVER['REQUEST_METHOD']=='POST')
{
	require ('../../moocms_connect.php');
	$errors = array();
	
	$lecture_id = mysqli_real_escape_string($dbc, trim($_POST['lecture_id']));
	
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
		$errors[] = 'Enter youtube video key.';
	}else{
		$lecture_video = mysqli_real_escape_string($dbc, trim($_POST['lecture_video']));
	}
		
	//update DATA in DATABASE
	if(empty($errors))
	{
		$x = "UPDATE lecture SET lecture_name = '$lecture_name', lecture_video = '$lecture_video' WHERE lecture_id = '$lecture_id'";
		$r1 = mysqli_query($dbc, $x);
		
		$y = "SELECT course_id from lecture WHERE lecture_id = '$lecture_id'";
		$r2 = mysqli_query($dbc, $y);
		$course_row = mysqli_fetch_array($r2, MYSQLI_ASSOC);
		$course_id = $course_row['course_id'];
		
		//confirm REGISTRATION
		if($r1 && $r2)
		{
			echo '<h1>Lecture Edited Succesfully</h1>';
			echo '<a href = "manage_course.php?id=' . $course_id . '"><<< &nbsp;Back</a>';
			
			//BOTTOM MENU
			echo '<p>
				  <a href="index.php">Home</a> |
				  <a href="instructing_courses.php">Instructing Courses</a> |
				  <a href="settings.php">Settings</a> |
				  <a href="logout.php">Logout</a>
				  </p>';
		}
		else
		{
			echo "query fail";
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

require ('../../moocms_connect.php');
$select_query1 = "SELECT * FROM lecture WHERE lecture_id = '$lecture_id'";
$result1 = mysqli_query($dbc, $select_query1);
$lecture_row = mysqli_fetch_array($result1);
$course_id = $lecture_row['course_id'];

//REGISTRATION FORM
echo '<h1>Edit lecture</h1>';
echo '<form action="edit_lecture.php" method="POST">';
echo '<input type = "hidden" name="lecture_id" value="'; echo $lecture_id; echo '">';
echo '<p>';
echo 'Lecture Name: <input type="text" name="lecture_name" value="'; echo $lecture_row['lecture_name']; echo'"/>';
echo '</p>';
echo '<p>';
echo 'Youtube Video Key: <input type="text" name="lecture_video" value="'; echo $lecture_row['lecture_video']; echo'"/>';
echo '</p>';
echo '<p>';
echo '<input type="submit" value="Edit Lecture">';
echo '</p>';
echo '</form>';

echo '<a href = "manage_course.php?id=' . $course_id . '"><<< &nbsp;Back</a>';

mysqli_close($dbc);

//BOTTOM MENU
echo '<p>
	  <a href="index.php">Home</a> |
	  <a href="instructing_courses.php">Instructing Courses</a> |
	  <a href="settings.php">Settings</a> |
	  <a href="logout.php">Logout</a>
	  </p>';


//PAGE FOOTER

?>