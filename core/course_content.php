<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['student_id']))
{
	require ('login_tools.php');
	load();
}

//get ID of ITEM
if(isset($_GET['id'])){
	$course_id = $_GET['id'];
}else{
	$course_id = 0;
}

$page_title = "Courses Content | MOOCMS";

//PAGE HEADER

//retrive STUDENT PROFILE data from SESSION
$student_id = $_SESSION['student_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

require ('../moocms_connect.php');

//retrive PROFILE data from DATABASE
$qc = "SELECT course_name FROM course where course_id = '$course_id'";
$rc = mysqli_query($dbc, $qc);
$course_row = mysqli_fetch_array($rc, MYSQLI_ASSOC);

if($_SERVER['REQUEST_METHOD']=='POST')
{
	$errors = array();
	
	$course_id = mysqli_real_escape_string($dbc, trim($_POST['course_id']));
	
	//check whether ATLEAST ONE search term is PRESENT
	if(empty($_POST['lecture_name']))
	{
		$errors[] = 'Enter atleast one search term.';
	}else{
		$lecture_name = mysqli_real_escape_string($dbc, trim($_POST['lecture_name']));
	}
	
	//search DATA in DATABASE
	if(empty($errors))
	{
		//APTANA STUDIOS shows ERROR in the line BELOW
		$q = "SELECT lecture_id, lecture_name, lecture_date FROM lecture WHERE course_id = '$course_id' AND lecture_name LIKE '%".mysql_real_escape_string($lecture_name)."%' LIMIT 0,20";
		$r = mysqli_query($dbc, $q);
		
		//check if MEMBERS exist in DATABASE
		if(mysqli_num_rows($r) >= 1)
		{
			echo "<h1>" . $course_row['course_name'] . "</h1>";
			
			echo "<p>Search Results</p>";
			
			echo '<table width="50%">
			<tr>
				<td><strong>Lecture Name</strong></td>
				<td><strong>Lecture Date</strong></td>
			</tr>';
	
			while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
			{
				//print DETAILS on SCREEN
				
				echo "<tr>
						<td><a href='view_lecture.php?id={$row['lecture_id']}'>{$row['lecture_name']}</td>
						<td>{$row['lecture_date']}</td>
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
			  <a href="my_courses.php">My Courses</a> |
			  <a href="browse_courses.php">Browse Courses</a> |
			  <a href="settings.php">Settings</a> |
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
			
	//SEARCH FORM
	echo '<form action="course_content.php" method="POST">
		  <input type="hidden" name="course_id" value="' . $course_id . '">
		  <b>Lecture Name:</b> <input type="text" name="lecture_name" size="90">';
	echo '<input type="submit" value="Search">
		  </form>';
	
	//set LIMIT for no. of USERS displayed per PAGE
	$rec_limit = 10;
		
	//count no. of ITEMS
	$q = "SELECT count(lecture_id) FROM lecture WHERE course_id = '$course_id'";
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
	$q = "SELECT lecture_id, lecture_name, lecture_date FROM lecture WHERE course_id = '$course_id' ORDER BY lecture_date ASC LIMIT $offset, $rec_limit";
	$r = mysqli_query($dbc, $q);
	
	if(!$r)
	{
		die('Could not get data: ' . mysql_error());
	}
	
	echo "<h1>" . $course_row['course_name'] . "</h1>";
	
	echo "<a href='leave_course.php?id={$course_id}'>Leave Course</a><br><br>";
	
	echo '<table width="50%">
			<tr>
				<td><strong>Lecture Name</strong></td>
				<td><strong>Lecture Date</strong></td>
			</tr>';
	
	while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
	{
		//print DETAILS on SCREEN
		
		echo "<tr>
				<td><a href='view_lecture.php?id={$row['lecture_id']}'>{$row['lecture_name']}</td>
				<td>{$row['lecture_date']}</td>
			  </tr>";
	}
	
	echo '</tr></table>';
	
	if($page == 0)
	{
		if($left_rec < $rec_limit)
		{
			echo "<br>Nothing more to display";
		
		}else{
			
			echo "<a href=\"manage_courses.php?page=$page\">Next 10 Records</a>";
			
		}
		
	
	}else if($left_rec < $rec_limit)
	{
		$last = $page - 2;
		echo "<a href=\"manage_courses.php?page=$last\">Last 10 Records</a>";
	
	}else{
		
		$last = $page - 2;
		echo "<a href=\"manage_courses.php?page=$last\">Last 10 Records</a> |";
		echo "<a href=\"manage_courses.php?page=$page\">Next 10 Records</a>";
		
	}
	
	mysqli_close($dbc);

}

//BOTTOM MENU
echo '<p>
	  <a href="index.php">Home</a> |
	  <a href="my_courses.php">My Courses</a> |
	  <a href="browse_courses.php">Browse Courses</a> |
	  <a href="settings.php">Settings</a> |
	  <a href="logout.php">Logout</a>
	  </p>';

//PAGE FOOTER


?>