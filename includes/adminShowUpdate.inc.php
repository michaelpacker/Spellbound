<?php

if (isset($_POST['show-update'])) {
	
	// Set up token in database
	require 'dbh.inc.php';
	
	$showID 			= $_POST["id"];
	$show_name 			= $_POST["show_name_update"];
	$show_name_short 	= $_POST["show_short_name_update"];
	$show_logo		 	= $_POST["show_logo_update"];

	$show_url 			= $_POST["show_url_update"];
	
	$sql = "SELECT * FROM shows WHERE id=?";
	$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			// If the sql statement fails
			echo "There was a sql error in finding the show.";
			exit();
		} else {
			// get the product
			mysqli_stmt_bind_param($stmt, "i", $showID);
			mysqli_stmt_execute($stmt);
	
			$result = mysqli_stmt_get_result($stmt);
			if (!$row = mysqli_fetch_assoc($result)) {
				echo "<p>There was a general error finding the show.</p>";
			} else {				
				

				$sql = "UPDATE shows SET showName =?, showAbrv =?, showURL =?, showLogo =? WHERE id=?";
				
				
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					// If the sql statement fails
					echo "There was an error updating the the show. Try again? I guess?";
					exit();
				} else { 
					// statement is fine, now execute the statement
					mysqli_stmt_bind_param($stmt, "ssssi", $show_name, $show_name_short, $show_url, $show_logo, $showID);
					mysqli_stmt_execute($stmt);
					
					header ("Location: ../adminShows.php?updateStatus=success&updatedName=$show_name");
				
				}
				
			}
		
		}

} else {
	header("Location: ../index.php");
	exit();
}