<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['admin_id']))
{
	require ('login_tools.php');
	load();
}

//PAGE TITLE
$page_title = 'Edit course | MOOCMS Admin';

//PAGE HEADER

//get ID of ITEM
if(isset($_GET['id'])){
	$course_id = $_GET['id'];
}else{
	$course_id = 0;
}

if($_SERVER['REQUEST_METHOD']=='POST')
{
	require ('../../moocms_connect.php');
	$errors = array();
	
	//check whether COURSE NAME is EMPTY
	if(empty($_POST['course_name']))
	{
		$errors[] = 'Enter course name.';
	}else{
		$course_name = mysqli_real_escape_string($dbc, trim($_POST['course_name']));
	}
	
	//check whether START DATE is EMPTY
	if(empty($_POST['start_date']))
	{
		$errors[] = 'Select start date.';
	}else{
		$start_date = mysqli_real_escape_string($dbc, trim($_POST['start_date']));
	}
	
	//check whether FACULTY is EMPTY
	if(empty($_POST['faculty']))
	{
		$faculty_id = "";
	}else{
		$faculty_id = mysqli_real_escape_string($dbc, trim($_POST['faculty']));
	}
	
	$course_id = mysqli_real_escape_string($dbc, trim($_POST['course_id']));

	
	//update DATA in DATABASE
	if(empty($errors))
	{
		$x = "UPDATE course SET course_name = '$course_name', start_date = '$start_date' WHERE course_id = '$course_id'";
		$r1 = mysqli_query($dbc, $x);
		
		$z = "UPDATE instructor SET faculty_id = '$faculty_id', reg_date = NOW() WHERE course_id = '$course_id'";
		$r3 = mysqli_query($dbc, $z);
		
		//confirm REGISTRATION
		if($r1 && $r3)
		{
			echo '<h1>Course Edited Succesfully</h1>';
			
			//BOTTOM MENU
			echo '<p>
				  <a href="index.php">Home</a> |
				  <a href="manage_students.php">Manage Students</a> |
				  <a href="manage_faculties.php">Manage Faculties</a> |
				  <a href="manage_courses.php">Manage Courses</a> |
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
$select_query1 = "SELECT * FROM course WHERE course_id = '$course_id'";
$result1 = mysqli_query($dbc, $select_query1);
$course_row = mysqli_fetch_array($result1);

$select_query2 = "SELECT * FROM instructor WHERE course_id = '$course_id'";
$result2 = mysqli_query($dbc, $select_query2);
$faculty_id_row = mysqli_fetch_array($result2);

$faculty_id = $faculty_id_row['faculty_id'];

if($faculty_id != 0)
{
	$select_query3 = "SELECT * FROM faculty WHERE faculty_id = '$faculty_id'";
	$result3 = mysqli_query($dbc, $select_query3);
	$instructor_row = mysqli_fetch_array($result3);
	$instructor_name = $instructor_row['first_name'] . " " . $instructor_row['last_name'];
}
else
{
	$faculty_name = "NONE";
}

$select_query = "SELECT faculty_id, first_name, last_name FROM faculty";
$result = mysqli_query($dbc, $select_query);

//REGISTRATION FORM
echo '<h1>Edit course</h1>';
echo '<form action="edit_course.php" method="POST">';
echo '<input type = "hidden" name="course_id" value="'; echo $course_id; echo '">';
echo '<p>';
echo 'Course Name: <input type="text" name="course_name" value="'; echo $course_row['course_name']; echo'"/>';
echo '</p>';
echo '<p>';
echo 'Start Date: <input type="date" name="start_date" value="'; echo $course_row['start_date']; echo'"/>';
echo '</p>';
echo '<p>';
echo 'Faculty: <select name="faculty" value="'; echo $instructor_name; echo'">';
echo '<option disabled="" selected="">Select Faculty</option>';

while($faculty_row = mysqli_fetch_array($result))
{
	echo "<option value='" . $faculty_row['faculty_id'] . "'>" . $faculty_row['first_name'] . " " . $faculty_row['last_name'] . "</option>"; 
}

echo '</select>';
echo '</p>';
echo '<p>';
echo '<input type="submit" value="Edit Course">';
echo '</p>';
echo '</form>';

mysqli_close($dbc);

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