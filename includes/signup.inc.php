<?php

if (isset($_POST['signup-submit'])) {
	
	require 'dbh.inc.php';
	
	$userEmail 	= $_POST['user_email'];
	$userFname 	= $_POST['user_first_name'];
	$userLname 	= $_POST['user_last_name'];
	$userPwd 	= $_POST['user_pwd'];
	$userPwdRepeat = $_POST['user_pwd_confirm'];
	
	if (empty($userEmail) || empty($userFname) || empty($userLname) || empty($userPwd)) {
		header("Location: ../signup.php?error=emptyFields&userEmail=".$userEmail);
		exit();
	}
	else if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../signup.php?error=invalidEmail&userEmail=".$userEmail);
		exit();
	}
	else if ($userPwd !== $userPwdRepeat) {
		header("Location: ../signup.php?error=passwordMatch&userEmail=".$userEmail);
		exit();	
	}
	else {
		// Use prepared statement in place of email variable to avoid injection attack
		$sql = "SELECT email FROM users WHERE email=?";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../signup.php?error=sqlError");
			exit();
		}
		else {
			// Give info to prepared statement
			// "s" is a string
			mysqli_stmt_bind_param($stmt, "s", $userEmail);
			mysqli_stmt_execute($stmt);
			// sql query has run
			// get result and store it in the statement
			mysqli_stmt_store_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt); // returned rows from query
		
			if ($resultCheck > 0) {
				// user exists
				header("Location: ../signup.php?error=userExists");
				exit();
			}
			else {
				// Insert the user
				$sql = "INSERT INTO users 
				(first_name, last_name, email, password) 
				VALUES 
				(?,?,?,?)";
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					header("Location: ../signup.php?error=sqlError");
					exit();
				}
				else {
					$hashedPwd = password_hash($userPwd, PASSWORD_DEFAULT);
					mysqli_stmt_bind_param($stmt, "ssss", $userFname, $userLname, $userEmail, $hashedPwd);
					mysqli_stmt_execute($stmt);
					header("Location: ../signup.php?signup=success");
					exit();
				}
				
			}
		}
	}
	// Close connection
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	
}
else {
	// User did not click submit to get here
	header("Location: ../gatewayEntreat.php");
	exit();
} 