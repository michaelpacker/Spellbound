<?php

if (isset($_POST['show-schedule'])) {
	
	// Set up token in database
	require 'dbh.inc.php';
	
	$sched_show_id			= $_POST["sched_show_name"];
	$sched_venue_id 		= $_POST["sched_show_venue"];
	$sched_begin 			= $_POST["sched_show_begin"];
	$sched_end				= $_POST["sched_show_end"];
	//$sched_begin .= ' 00:00:00';
	
	$show_name = '';
	$sched_id = '';
	
	// Create a unique ID out of exisitng stuff
	// This is a string
	$begDate = new DateTime($sched_begin);
	$formatted_beg_date = $begDate->format('mY');
	$sched_id = $sched_show_id . $formatted_beg_date;
	
	
	$sql = "SELECT showName FROM shows WHERE shows.id = $sched_show_id";
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_assoc($result)) {
		$show_name = $row['showName'];
	}
	
	
	// Insert the location
	
	 $sql = "INSERT INTO show_schedule  
			(scheduleID,showID,locationID,beginDate,endDate) 
			VALUES 
			(?,?,?,?,?)";

		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../adminShows.php?error=sqlError");
			exit();
		} else {
			mysqli_stmt_bind_param($stmt, "iiiss", $sched_id, $sched_show_id, $sched_venue_id, $sched_begin, $sched_end);
			mysqli_stmt_execute($stmt);
			header("Location: ../adminShows.php?createStatus=success&createdName=$show_name");
			exit();
		}
	
} else {
	// product-create was not pressed
	header("Location: ../index.php");
	exit();
}