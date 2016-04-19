<?php

//LOG-IN USER
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	require ('../../moocms_connect.php');
	require ('login_tools.php');
	
	//CHECK for VALIDITY and RETURN user DETAILS
	list($check, $data) = validate($dbc, $_POST['email'], $_POST['pass']);
	
	if($check)
	{
		session_start();
		
		//fetch DETAILS from validate FUNCTION
		$_SESSION['admin_id'] = $data['admin_id'];
		$_SESSION['first_name'] = $data['first_name'];
		$_SESSION['last_name'] = $data['last_name'];
		
		load('index.php');
	}else{
		$errors = $data;
	}
	
	mysqli_close($dbc);
}

include ('login.php');

?>