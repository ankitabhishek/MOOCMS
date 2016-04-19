<?php

session_start();

//if user is LOGGED-IN redirect to HOME
if(isset($_SESSION['admin_id']))
{
	header('Location: index.php');
}


//PAGE TITLE
$page_title = 'Register';

//PAGE HEADER


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
	
	//check whether E-MAIL is EMPTY
	if(empty($_POST['email']))
	{
		$errors[] = 'Enter your e-mail address.';
	}else{
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
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
	}else{
		$errors[] = 'Enter your password.';
	}
	
	//check whether E-MAIL already EXISTS
	if(empty($errors))
	{
		$q = "SELECT student_id FROM student WHERE email = '$email'";
		$r = mysqli_query($dbc, $q);
		
		if(mysqli_num_rows($r) != 0)
		{
			$errors[] = 'E-mail address already registered. <a href = "login.php">LOGIN</a>';
		}
	}
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	    $ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	    $ip = $_SERVER['REMOTE_ADDR'];
	}
	
	//insert DATA into DATABASE
	if(empty($errors))
	{
		$q = "INSERT INTO admin (pass, first_name, last_name, email, last_login, last_ip, reg_date) VALUES (SHA1('$pass'), '$first_name', '$last_name', '$email', NOW(), '$ip', NOW())";
		$r = mysqli_query($dbc, $q);
		
		//confirm REGISTRATION
		if($r)
		{
			echo '<h1>Registered</h1>
				  <p>You are now registered.</p>
				  <p><a href="login.php">LOGIN</a></p>';
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

?>

<!-- REGISTRATION FORM -->
<h1>Register</h1>
<form action="register.php" method="POST">
	<p>
		First Name: <input type="text" name="first_name" value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name'];?>"/>
		Last Name: <input type="text" name="last_name" value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name'];?>"/>
	</p>
	<p>
		E-mail Address: <input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>"/>
	</p>
	<p>
		Password: <input type="password" name="pass" value="<?php if(isset($_POST['pass'])) echo $_POST['pass'];?>"/>
		Confirm Password: <input type="password" name="pass2" value="<?php if(isset($_POST['pass2'])) echo $_POST['pass2'];?>"/>
	</p>
	<p>
		<input type="submit" value="Register">
	</p>
</form>

<p>or, <a href="login.php">LOGIN</a></p>


<!-- PAGE FOOTER -->
