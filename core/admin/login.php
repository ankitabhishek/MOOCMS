<?php

session_start();

//if already LOGGED-IN redirect to HOME
if(isset($_SESSION['student_id']))
{
	header('Location: index.php');
}

$page_title = 'Login';

//PAGE HEADER


//check for ERROR(S)
if(isset($errors) && !empty($errors))
{
	echo '<p id="err_msg">Opps! There was a problem:<br>';
	
	foreach ($errors as $msg)
	{
		echo " - $msg<br>";		
	}
	
	echo 'Please try again or <a href="register.php">REGISTER</a></p>';
}

?>

<!-- LOGIN FORM -->
<h1>Administrator Login</h1>
<form action="login_action.php" method="POST">
	<p>
		Email Address: <input type="text" name="email" />
	</p>
	<p>
		Password: <input type="password" name="pass" />
	</p>
	<p>
		<input type="submit" value="Login" />
	</p>
</form>


<?php
//PAGE FOOTER

?>