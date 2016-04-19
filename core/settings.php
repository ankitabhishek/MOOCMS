<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['student_id']))
{
	require ('login_tools.php');
	load();
}

//PAGE TITLE
$page_title = 'Account settings | MOOCMS';

//PAGE HEADER

//retrive FACULTY PROFILE data from SESSION
$student_id = $_SESSION['student_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];


if($_SERVER['REQUEST_METHOD']=='POST')
{
	require ('../moocms_connect.php');
	$errors = array();
	
	//check whether PASSWORD is EMPTY and the two PASSWORDS MATCH
	if(!empty($_POST['pass']))
	{
		if($_POST['pass'] != $_POST['pass2'])
		{
			$errors[] = 'Passwords do not match.';
		}else{
			$pass = mysqli_real_escape_string($dbc, trim($_POST['pass']));
		}
	}else{
		$errors[] = 'Enter your password.';
	}
	
	//update DATA into DATABASE
	if(empty($errors))
	{
		$q = "UPDATE student SET pass = SHA1('$pass') WHERE student_id = '$student_id'";
		$r = mysqli_query($dbc, $q);
		
		//confirm REGISTRATION
		if($r)
		{
			echo '<h1>Password changed successfully</h1>
				  <p><a href="settings.php"><<< &nbsp;Back</a></p>';
			
			//BOTTOM MENU
			echo '<p>
				  <a href="index.php">Home</a> |
				  <a href="my_courses.php">My Courses</a> |
				  <a href="browse_courses.php">Browse Courses</a> |
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


//Settings FORM
echo '<h1>Account Settings</h1>';
echo '<h3>Change Password</h3>';
echo '<form action="settings.php" method="POST">';
echo '<p>';
echo 'Password: <input type="password" name="pass" value="'; if(isset($_POST['pass'])) echo $_POST['pass']; echo '"/>';
echo '<br><br>';
echo 'Confirm Password: <input type="password" name="pass2" value="'; if(isset($_POST['pass2'])) echo $_POST['pass2']; echo '"/>';
echo '</p>';
echo '<p>';
echo '<input type="submit" value="Change Password">';
echo '</p>';
echo '</form>';

//PAGE FOOTER

//BOTTOM MENU
echo '<p>
	  <a href="index.php">Home</a> |
	  <a href="my_courses.php">My Courses</a> |
	  <a href="browse_courses.php">Browse Courses</a> |
	  <a href="settings.php">Settings</a> |
	  <a href="logout.php">Logout</a>
	  </p>';
?>