<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['faculty_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "Manage Course | MOOCMS Faculty";

//PAGE HEADER

//get ID of ITEM
if(isset($_GET['id'])){
	$course_id = $_GET['id'];
}else{
	$course_id = 0;
}

//retrive FACULTY PROFILE data from SESSION
$faculty_id = $_SESSION['faculty_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

require ('../../moocms_connect.php');

//giving redirect loop
/*
//check if course belong to faculty
$q1 = "SELECT faculty_id FROM instructor WHERE course_id = '$course_id'";
$r1 = mysqli_query($dbc, $q1);
$instructor_row = mysqli_fetch_array($r1, MYSQLI_ASSOC);

if($instructor_row['faculty_id'] != $faculty_id)
{
	header("Location: manage_course.php");
}
*/

if($_SERVER['REQUEST_METHOD']=='POST')
{
	$errors = array();
	
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
		$q = "SELECT lecture_id, lecture_name, lecture_date FROM lecture WHERE lecture_name LIKE '%".mysql_real_escape_string($lecture_name)."%' AND course_id = '$course_id'";
		$r = mysqli_query($dbc, $q);
		
		//check if MEMBERS exist in DATABASE
		if(mysqli_num_rows($r) >= 1)
		{
			echo "<p>Search Results</p>";
			
			echo '<table width="50%">
			<tr>
				<td><strong>Lecture ID</strong></td>
				<td><strong>Lecture Name</strong></td>
				<td><strong>Lecture Date</strong></td>
			</tr>';
	
			while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
			{
				//print DETAILS on SCREEN
				
				echo "<tr>
						<td><a href='edit_lecture.php?id={$row['lecture_id']}'>{$row['lecture_id']}</a></td>
						<td>{$row['lecture_name']}</td>
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
			  <a href="instructing_courses.php">Instructing Courses</a> |
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
	
	echo "Search Lectures";
		
	//SEARCH FORM
	echo '<form action="manage_course.php?id=' . $course_id . '" method="POST">
		  <b>Lecture Name:</b> <input type="text" name="lecture_name" size="90">';
	echo '<input type="submit" value="Search">
		  </form>';
		  
	echo '<a href="add_lecture.php?id=' . $course_id . '">Add Lecture</a>';
	
	//set LIMIT for no. of USERS displayed per PAGE
	$rec_limit = 10;
		
	//count no. of ITEMS
	$q = "SELECT count(lecture_id) FROM lecture WHERE course_id = '$course_id'";
	$r = mysqli_query($dbc, $q);
	
	if(!$r)
	{
		die('Could not get data: up' . mysql_error());
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
		die('Could not get data: here' . mysql_error());
	}
	
	echo "<h1>Course Lectures</h1>";
	
	echo '<table width="50%">
			<tr>
				<td><strong>Lecture ID</strong></td>
				<td><strong>Lecture Name</strong></td>
				<td><strong>Lecture Date</strong></td>
			</tr>';
	
	while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
	{
		//print DETAILS on SCREEN
		
		echo "<tr>
				<td><a href='edit_lecture.php?id={$row['lecture_id']}'>{$row['lecture_id']}</a></td>
				<td>{$row['lecture_name']}</td>
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
			
			echo "<a href=\"manage_course.php?page=$page\">Next 10 Records</a>";
			
		}
		
	
	}else if($left_rec < $rec_limit)
	{
		$last = $page - 2;
		echo "<a href=\"manage_course.php?page=$last\">Last 10 Records</a>";
	
	}else{
		
		$last = $page - 2;
		echo "<a href=\"manage_course.php?page=$last\">Last 10 Records</a> |";
		echo "<a href=\"manage_course.php?page=$page\">Next 10 Records</a>";
		
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