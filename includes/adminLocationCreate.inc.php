<?php

if (isset($_POST['location-create'])) {
	
	// Set up token in database
	require 'dbh.inc.php';
	
	$loc_name	= $_POST["location_name"];
	$loc_street = $_POST["location_street"];
	$loc_city 	= $_POST["location_city"];
	$loc_state 	= $_POST["location_state"];
	$loc_zip	= $_POST["location_zip"];
	
	// See if location already exists
	$sql = "SELECT venue FROM show_locations WHERE venue=? AND city=?";
	$stmt = mysqli_stmt_init($conn);

	
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		// sql error
		header("Location: ../adminShows.php?createStatus=error&message=sql");
	} else {
		
		mysqli_stmt_bind_param($stmt, "ss", $loc_name, $loc_city);
		mysqli_stmt_execute($stmt);
		
		mysqli_stmt_store_result($stmt);
		$result_check = mysqli_stmt_num_rows($stmt);
		
		if ($result_check > 0) {
			// Location exists
			
			header("Location: ../adminShows.php?createStatus=error&message=name");
		} else {
			// Insert the location
			$sql = "INSERT INTO show_locations 
				(venue,street,city,state,zip) 
				VALUES 
				(?,?,?,?,?)";
			
			$stmt = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				header("Location: ../adminShows.php?error=sqlError");
				exit();
			} else {
				mysqli_stmt_bind_param($stmt, "sssss", $loc_name,$loc_street,$loc_city,$loc_state,$loc_zip);
				mysqli_stmt_execute($stmt);
				header("Location: ../adminShows.php?createStatus=success&createdName=$loc_name");
				exit();
			}
		}
	}

} else {
	// product-create was not pressed
	header("Location: ../index.php");
	exit();
}