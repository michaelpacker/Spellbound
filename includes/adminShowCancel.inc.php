<?php

if (isset($_POST['show-cancel'])) { 

	// Set up token in database
	require 'dbh.inc.php';
	
	$showID 		= $_POST['cancelled-show-id'];
	$show_name 		= $_POST['cancelled-show-name'];
	$show_start 	= $_POST['cancelled-show-start'];
	$show_end 		= $_POST['cancelled-show-end'];
	$schedule_str	= $_POST['cancelled-show-scheduleID'];
	
	
	$sql = "SELECT * FROM show_schedule WHERE scheduleID =? AND showID =?";
	$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			// If the sql statement fails
			echo "There was a sql error in connecting.";
			exit();
		} else {
			// get the scheduled entry
			mysqli_stmt_bind_param($stmt, "si", $schedule_str, $showID);
			mysqli_stmt_execute($stmt);
			
			$result = mysqli_stmt_get_result($stmt);
			if (!$row = mysqli_fetch_assoc($result)) {
				echo "<p>There was a general error finding the show.</p>";
			} else { 				
				$sql = "DELETE FROM show_schedule WHERE scheduleID =? AND showID=?";
				
				
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					// If the sql statement fails
					echo "There was an error deleting the the show. Try again? I guess?";
					exit();
				} else { 
					// statement is fine, now execute the statement
					mysqli_stmt_bind_param($stmt, "si", $schedule_str, $showID);
					mysqli_stmt_execute($stmt);
					
					header ("Location: ../adminShows.php?updateStatus=success&updatedName=$show_name");
				
				}
				
				
			}
		}
	
	
} else {
	// something else big happened
	header("Location: ../index.php");
	exit();
}