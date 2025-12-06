<?php

if (isset($_POST['venue-update'])) {
	
	// Set up token in database
	require 'dbh.inc.php';
	
	$venueID 			= $_POST["id"];
	$venue_name 		= $_POST["venue_name_update"];
	$venue_street 		= $_POST["venue_street_update"];
	$venue_city		 	= $_POST["venue_city_update"];
	$venue_state 		= $_POST["venue_state_update"];
	$venue_zip			= $_POST["venue_zip_update"];
	
	$sql = "SELECT * FROM show_locations WHERE id=?";
	$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			// If the sql statement fails
			echo "There was a sql error in finding the venue in the table show_locations.";
			exit();
		} else {
			// get the product
			mysqli_stmt_bind_param($stmt, "i", $venueID);
			mysqli_stmt_execute($stmt);
	
			$result = mysqli_stmt_get_result($stmt);
			if (!$row = mysqli_fetch_assoc($result)) {
				echo "<p>There was a general error finding the show.</p>";
			} else {				
				

				$sql = "UPDATE show_locations SET venue =?, street =?, city =?, state =?, zip =? WHERE id=?";
				
				
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					// If the sql statement fails
					echo "There was an error updating the venue. Try again? I guess?";
					exit();
				} else { 
					// statement is fine, now execute the statement
					mysqli_stmt_bind_param($stmt, "ssssii", $venue_name, $venue_street, $venue_city, $venue_state, $venue_zip, $venueID);
					mysqli_stmt_execute($stmt);
					
					header ("Location: ../adminShows.php?updateStatus=success&updatedName=$venue_name");
				
				}
				
			}
		
		}

} else {
	header("Location: ../index.php");
	exit();
}