<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['admin_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "Manage Faculties | MOOCMS Admin";

//PAGE HEADER

//retrive ADMIN PROFILE data from SESSION
$id = $_SESSION['admin_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

require ('../../moocms_connect.php');

if($_SERVER['REQUEST_METHOD']=='POST')
{
	$errors = array();
	
	//check whether ATLEAST ONE search term is PRESENT
	if(empty($_POST['first_name']) && empty($_POST['last_name']) && empty($_POST['email']))
	{
		$errors[] = 'Enter atleast one search term.';
	}else{
		$first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
		$last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}
	
	//search DATA in DATABASE
	if(empty($errors))
	{
		//APTANA STUDIOS shows ERROR in the line BELOW
		$q = "SELECT faculty_id, first_name, last_name, email FROM faculty WHERE first_name = '".mysql_real_escape_string($first_name)."' OR last_name = '".mysql_real_escape_string($last_name)."' OR email = '".mysql_real_escape_string($email)."' LIMIT 0,20";
		$r = mysqli_query($dbc, $q);
		
		//check if MEMBERS exist in DATABASE
		if(mysqli_num_rows($r) >= 1)
		{
			echo "<p>Search Results</p>";
			
			echo '<table width="75%">
			<tr>
				<td>Faculty ID</td>
				<td>First Name</td>
				<td>Last Name</td>
				<td>Email ID</td>
			</tr>';
	
			while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
			{
				//print DETAILS on SCREEN
				
				echo "<tr>
						<td><a href='edit_faculty.php?id={$row['faculty_id']}'>{$row['faculty_id']}</a></td>
						<td>{$row['first_name']}</td>
						<td>{$row['last_name']}</td>
						<td>{$row['email']}</td>
					  </tr>";
			}
			
			echo '</tr></table>';
			
			//close CONNECTION with DATABASE
			mysqli_close($dbc);
		}
		//if NO MEMBER exist in DATABASE
		else{
			
			echo '<p>There are no members related to your search query.</p>';
			
			//close CONNECTION with DATABASE
			mysqli_close($dbc);
		}
		
		//BOTTOM MENU
		echo '<p>
			  <a href="index.php">Home</a> |
			  <a href="manage_students.php">Manage Students</a> |
			  <a href="manage_faculties.php">Manage Faculties</a> |
			  <a href="manage_courses.php">Manage Courses</a> |
			  <a href="logout.php">Logout</a>
			  </p>';
		
		//PAGE FOOTER
		
		
		exit();
	}
	//display ERROR(S) if searching FAILS
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
}else{
	
	echo "Search Faculties";
		
	//SEARCH FORM
	echo '<form action="manage_faculties.php" method="POST">
		  <b>First Name:</b> <input type="text" name="first_name" size="50">
		  <b>Last Name:</b> <input type="text" name="last_name" size="50">
		  <b>E-Mail</b> <input type="text" name="email" size="50"> <br>';
		  /*<b>Results:</b> <select name="results">
		  					<option>10</option>
    						<option>20</option>
    						<option>50</option>
						  </select><br>*/
	echo '<input type="submit" value="Search">
		  </form>';
	
	//set LIMIT for no. of USERS displayed per PAGE
	$rec_limit = 10;
		
	//count no. of ITEMS
	$q = "SELECT count(faculty_id) FROM faculty";
	$r = mysqli_query($dbc, $q);
	
	if(!$r)
	{
		die('Could not get data: ' . mysql_error());
	}
	
	$row = mysqli_fetch_array($r, MYSQLI_NUM);
	$rec_count = $row[0];
	
	if(isset($_GET{'page'}))
	{
		$page = $_GET{'page'} + 1;
		$offset = $rec_limit * $page ;
	
	}else{
		$page = 0;
		$offset = 0;
	}
	
	$left_rec = $rec_count - ($page * $rec_limit) - 1;
	
	//retrive PROFILE data from DATABASE
	$q = "SELECT faculty_id, first_name, last_name, email FROM faculty ORDER BY first_name ASC LIMIT $offset, $rec_limit";
	$r = mysqli_query($dbc, $q);
	
	if(!$r)
	{
		die('Could not get data: ' . mysql_error());
	}
	
	echo "<h1>Browsing Faculties</h1>";
	
	echo '<table width="75%">
			<tr>
				<td><strong>Faculty ID</strong></td>
				<td><strong>First Name</strong></td>
				<td><strong>Last Name</strong></td>
				<td><strong>Email ID</strong></td>
			</tr>';
	
	while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
	{
		//print DETAILS on SCREEN
		
		echo "<tr>
				<td><a href='edit_faculty.php?id={$row['faculty_id']}'>{$row['faculty_id']}</a></td>
				<td>{$row['first_name']}</td>
				<td>{$row['last_name']}</td>
				<td>{$row['email']}</td>
			  </tr>";
	}
	
	echo '</tr></table>';
	
	
	if($page == 0)
	{
		if($left_rec < $rec_limit)
		{
			echo "<br>Nothing more to display";
		
		}else{
			
			echo "<a href=\"manage_faculties.php?page=$page\">Next 10 Records</a>";
			
		}
		
	
	}else if($left_rec < $rec_limit)
	{
		$last = $page - 2;
		echo "<a href=\"manage_faculties.php?page=$last\">Last 10 Records</a>";
	
	}else{
		
		$last = $page - 2;
		echo "<a href=\"manage_faculties.php?page=$last\">Last 10 Records</a> |";
		echo "<a href=\"manage_faculties.php?page=$page\">Next 10 Records</a>";
		
	}
	
	mysqli_close($dbc);

}
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