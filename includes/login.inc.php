<?php

if (isset($_POST['login-submit'])) { 
	
	require 'dbh.inc.php';

	$userEmail 	= $_POST['user_email'];
	$userPwd 	= $_POST['user_pwd'];
	
	if (empty($userEmail) || empty($userPwd)) {
		// Empty fields were sent
		// Should never happen
		header("Location: ../adminLogin.php?error=emptyFields");
		exit();
	} else {
		// Fields are populated - check db
		$sql = "SELECT * FROM users WHERE email=? ";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			// There was a problem with SQL
			header("Location: ../adminLogin.php?error=sqlError");
			exit();
		} else {
			// SQL is fine, process statement
			mysqli_stmt_bind_param($stmt, "s", $userEmail);
			mysqli_stmt_execute($stmt);
			
			$result = mysqli_stmt_get_result($stmt);
			
			if ($row = mysqli_fetch_assoc($result)) {
				// We have results from the databaste
				// we have a match on the email address
				
				$pwdCheck = password_verify($userPwd, $row['password']);
				
				if($pwdCheck == false) {
					// Bad password
					// the supplied password did not match what we have for the user
					header("Location: ../adminLogin.php?error=invalidCredentials");
					exit();
				} else if ($pwdCheck == true) {
					// Good password
					
					// START SESSION
					// GLOBAL VARIABLES
					session_start();
					$_SESSION['user-first-name'] 	= $row['first_name'];
					$_SESSION['user-last-name'] 	= $row['last_name'];
					$_SESSION['user-email'] 		= $row['email'];
					$_SESSION['user-id'] 			= $row['id'];
					
					header("Location: ../admin.php");
					exit();
					// START SESSION
					// GLOBAL VARIABLES
					
				} else {
					// Password was something else???
					header("Location: ../adminLogin.php?error=unknown");
					exit();
				}
				
				
			} else {
				// No results were returned
				// User not found
				header("Location: ../adminLogin.php?error=noResultsWereFound");
				exit();
			}
		}
	}
	
	
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}
else {
	// User did not click submit to get here
	header("Location: ../index.php");
	exit();
} 