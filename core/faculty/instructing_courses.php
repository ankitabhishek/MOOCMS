<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['faculty_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "Instructing Courses | MOOCMS Faculty";

//PAGE HEADER

//retrive ADMIN PROFILE data from SESSION
$faculty_id = $_SESSION['faculty_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

require ('../../moocms_connect.php');

if($_SERVER['REQUEST_METHOD']=='POST')
{
	$errors = array();
	
	//check whether ATLEAST ONE search term is PRESENT
	if(empty($_POST['course_name']))
	{
		$errors[] = 'Enter atleast one search term.';
	}else{
		$course_name = mysqli_real_escape_string($dbc, trim($_POST['course_name']));
	}
	
	//search DATA in DATABASE
	if(empty($errors))
	{
		//APTANA STUDIOS shows ERROR in the line BELOW
		$q = "SELECT c.course_id, c.course_name, c.start_date FROM course c, instructor i WHERE c.course_id = i.course_id AND c.course_name LIKE '%".mysql_real_escape_string($course_name)."%' AND i.faculty_id = '$faculty_id' LIMIT 0,20";
		$r = mysqli_query($dbc, $q);
		
		//check if MEMBERS exist in DATABASE
		if(mysqli_num_rows($r) >= 1)
		{
			echo "<p>Search Results</p>";
			
			echo '<table width="50%">
			<tr>
				<td><strong>Course ID</strong></td>
				<td><strong>Course Name</strong></td>
				<td><strong>Start Date<strong></td>
			</tr>';
	
			while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
			{
				//print DETAILS on SCREEN
				
				echo "<tr>
						<td><a href='manage_course.php?id={$row['course_id']}'>{$row['course_id']}</a></td>
						<td>{$row['course_name']}</td>
						<td>{$row['start_date']}</td>
					  </tr>";
			}
			
			echo '</tr></table>';
			
			//close CONNECTION with DATABASE
			mysqli_close($dbc);
		}
		//if NO MEMBER exist in DATABASE
		else{
			
			echo '<p>There are no coursess related to your search query.</p>';
			
			//close CONNECTION with DATABASE
			mysqli_close($dbc);
		}
		
		//BOTTOM MENU
		echo '<p>
			  <a href="index.php">Home</a> |
			  <a href="manage_students.php">Manage Students</a> |
			  <a href="manage_faculties.php">Manage Faculties</a> |
			  <a href="instructing_courses.php">Manage Courses</a> |
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
	
	echo "Search Courses You Instruct";
		
	//SEARCH FORM
	echo '<form action="instructing_courses.php" method="POST">
		  <b>Course Name:</b> <input type="text" name="course_name" size="90">';
	echo '<input type="submit" value="Search">
		  </form>';
	
	//set LIMIT for no. of USERS displayed per PAGE
	$rec_limit = 10;
		
	//count no. of ITEMS
	$q = "SELECT count(course_id) FROM instructor WHERE faculty_id = '$faculty_id'";
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
	$q = "SELECT c.course_id, c.course_name, c.start_date FROM course c, instructor i WHERE c.course_id = i.course_id AND i.faculty_id = '$faculty_id' ORDER BY course_name ASC LIMIT $offset, $rec_limit";
	$r = mysqli_query($dbc, $q);
	
	if(!$r)
	{
		die('Could not get data: ' . mysql_error());
	}
	
	echo "<h1>Browsing Courses You Instruct</h1>";
	
	echo '<table width="50%">
			<tr>
				<td><strong>Course ID</strong></td>
				<td><strong>Course Name</strong></td>
				<td><strong>Start Date</strong></td>
			</tr>';
	
	while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
	{
		//print DETAILS on SCREEN
		
		echo "<tr>
				<td><a href='manage_course.php?id={$row['course_id']}'>{$row['course_id']}</a></td>
				<td>{$row['course_name']}</td>
				<td>{$row['start_date']}</td>
			  </tr>";
	}
	
	echo '</tr></table>';
	
	if($page == 0)
	{
		if($left_rec < $rec_limit)
		{
			echo "<br>Nothing more to display";
		
		}else{
			
			echo "<a href=\"instructing_courses.php?page=$page\">Next 10 Records</a>";
			
		}
		
	
	}else if($left_rec < $rec_limit)
	{
		$last = $page - 2;
		echo "<a href=\"instructing_courses.php?page=$last\">Last 10 Records</a>";
	
	}else{
		
		$last = $page - 2;
		echo "<a href=\"instructing_courses.php?page=$last\">Last 10 Records</a> |";
		echo "<a href=\"instructing_courses.php?page=$page\">Next 10 Records</a>";
		
	}
	
	mysqli_close($dbc);

}

//BOTTOM MENU
echo '<p>
	  <a href="index.php">Home</a> |
	  <a href="instructing_courses.php">Instructing Courses</a> |
	  <a href="settings.php">Settings</a> |
	  <a href="logout.php">Logout</a>
	  </p>';

//PAGE FOOTER


?>