<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['admin_id']))
{
	require ('login_tools.php');
	load();
}

//PAGE TITLE
$page_title = 'Edit faculty | MOOCMS Admin';

//PAGE HEADER

//get ID of ITEM
if(isset($_GET['id'])){
	$faculty_id = $_GET['id'];
}else{
	$faculty_id = 0;
}

if($_SERVER['REQUEST_METHOD']=='POST')
{
	require ('../../moocms_connect.php');
	$errors = array();
	
	//check whether FIRST NAME is EMPTY
	if(empty($_POST['first_name']))
	{
		$errors[] = 'Enter your first name.';
	}else{
		$first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}
	
	//check whether LAST NAME is EMPTY
	if(empty($_POST['last_name']))
	{
		$errors[] = 'Enter your last name.';
	}else{
		$last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}
	
	//check whether PASSWORD is EMPTY and the two PASSWORDS MATCH
	if(!empty($_POST['pass']))
	{
		if($_POST['pass'] != $_POST['pass2'])
		{
			$errors[] = 'Passwords do not match.';
		}else{
			$pass = mysqli_real_escape_string($dbc, trim($_POST['pass']));
		}
	}
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	    $ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	    $ip = $_SERVER['REMOTE_ADDR'];
	}

	$faculty_id = mysqli_real_escape_string($dbc, trim($_POST['faculty_id']));
	
	//update DATA in DATABASE
	if(empty($errors))
	{
		if(empty($_POST['pass']))
		{
			$x = "UPDATE faculty SET first_name = '$first_name', last_name = '$last_name', last_ip = '$ip' WHERE faculty_id = '$faculty_id'";
		}
		else
		{
			$x = "UPDATE faculty SET pass = SHA1('$pass'), first_name = '$first_name', last_name = '$last_name', last_ip = '$ip' WHERE faculty_id = '$faculty_id'";
		}
		$r1 = mysqli_query($dbc, $x);

		//confirm UPDATION
		if($r1)
		{
			echo '<h1>Faculty Edited Succesfully</h1>';
			
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
$select_query1 = "SELECT first_name, last_name, email FROM faculty WHERE faculty_id = '$faculty_id'";
$result1 = mysqli_query($dbc, $select_query1);
$faculty_row = mysqli_fetch_array($result1);

//REGISTRATION FORM
echo '<h1>Edit faculty</h1>';
echo '<form action="edit_faculty.php" method="POST">';
echo '<input type = "hidden" name="faculty_id" value="'; echo $faculty_id; echo '">';
echo '<p>';
echo 'First Name: <input type="text" name="first_name" value="'; echo $faculty_row['first_name']; echo'"/>';
echo '</p>';
echo '<p>';
echo 'Last Name: <input type="text" name="last_name" value="'; echo $faculty_row['last_name']; echo'"/>';
echo '</p>';
echo '<p>';
echo 'Password <input type="password" name="pass"/>';
echo '</p>';
echo '<p>';
echo 'Confirm Password: <input type="password" name="pass2"/>';
echo '</p>';
echo '<p>';
echo '<input type="submit" value="Edit Faculty">';
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