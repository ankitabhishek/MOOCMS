<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['admin_id']))
{
	require ('login_tools.php');
	load();
}

//PAGE TITLE
$page_title = 'Add a course | MOOCMS Admin';

//PAGE HEADER


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

	//insert DATA into DATABASE
	if(empty($errors))
	{
		$x = "INSERT INTO course (course_name, start_date) VALUES ('$course_name', '$start_date')";
		$r1 = mysqli_query($dbc, $x);
		
		$y = "SELECT course_id FROM course ORDER BY course_id DESC LIMIT 1";
		$r2 = mysqli_query($dbc, $y);
		$course_row = mysqli_fetch_array($r2);
		$course_id = $course_row[0];
		
		
		$z = "INSERT INTO instructor (course_id, faculty_id, reg_date) VALUES ('$course_id', '$faculty_id', NOW())";
		$r3 = mysqli_query($dbc, $z);
		
		//confirm REGISTRATION
		if($r1 && $r2 && $r3)
		{
			echo '<h1>Course Added Succesfully</h1>';
			
			//BOTTOM MENU
			echo '<p>
				  <a href="index.php">Home</a> |
				  <a href="manage_students.php">Manage Students</a> |
				  <a href="manage_faculties.php">Manage Faculties</a> |
				  <a href="manage_courses.php">Manage Courses</a> |
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

require ('../../moocms_connect.php');
$select_query = "SELECT faculty_id, first_name, last_name FROM faculty";
$result = mysqli_query($dbc, $select_query);

//REGISTRATION FORM
echo '<h1>Add a course</h1>';
echo '<form action="add_course.php" method="POST">';
echo '<p>';
echo 'Course Name: <input type="text" name="course_name" value="'; if(isset($_POST['course_name'])) echo $_POST['course_name']; echo'"/>';
echo '</p>';
echo '<p>';
echo 'Start Date: <input type="date" name="start_date" value="'; if(isset($_POST['start_date'])) echo $_POST['start_date']; echo'"/>';
echo '</p>';
echo '<p>';
echo 'Faculty: <select name="faculty" value="'; if(isset($_POST['course_name'])) echo $_POST['course_name']; echo'">';
echo '<option disabled="" selected="">Select Faculty</option>';

while($faculty_row = mysqli_fetch_array($result))
{
	echo "<option value='" . $faculty_row['faculty_id'] . "'>" . $faculty_row['first_name'] . " " . $faculty_row['last_name'] . "</option>"; 
}

echo '</select>';
echo '</p>';
echo '<p>';
echo '<input type="submit" value="Add Course">';
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