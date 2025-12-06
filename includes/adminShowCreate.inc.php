<?php

if (isset($_POST['show-create'])) {
	
	// Set up token in database
	require 'dbh.inc.php';
	
	$shw_name	= $_POST["show_name"];
	$shw_abrv	= $_POST["show_abrv"];
	$shw_url = $_POST["show_url"];
	$shw_logo 	= $_POST["show_logo"];
	
	// See if location already exists
	$sql = "SELECT showName FROM shows WHERE showName=?";
	$stmt = mysqli_stmt_init($conn);

	
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		// sql error
		header("Location: ../adminShows.php?createStatus=error&message=sql");
	} else {
		
		mysqli_stmt_bind_param($stmt, "s", $shw_name);
		mysqli_stmt_execute($stmt);
		
		mysqli_stmt_store_result($stmt);
		$result_check = mysqli_stmt_num_rows($stmt);
		
		if ($result_check > 0) {
			// Location exists
			
			header("Location: ../adminShows.php?createStatus=error&message=name");
		} else {
			// Insert the location
			$sql = "INSERT INTO shows 
				(showName,showAbrv,showURL,showLogo) 
				VALUES 
				(?,?,?,?)";
			
			$stmt = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				header("Location: ../adminShows.php?error=sqlError");
				exit();
			} else {
				mysqli_stmt_bind_param($stmt, "ssss", $shw_name, $shw_abrv, $shw_url, $shw_logo);
				mysqli_stmt_execute($stmt);
				header("Location: ../adminShows.php?createStatus=success&createdName=$shw_name");
				exit();
			}
		}
	}

} else {
	// product-create was not pressed
	header("Location: ../index.php");
	exit();
}