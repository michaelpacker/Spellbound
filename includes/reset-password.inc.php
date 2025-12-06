<?php

if (isset($_POST["reset-password-submit"])) {
	
	// See if this is the correct user
	$selector = $_POST["selector"];
	$validator = $_POST["validator"];
	$password = $_POST["pwd"];
	$passwordRepeat = $_POST["pwd-repeat"];
	
	if (empty($selector) || empty($validator)) {
		header ("Location: ../passwordReset.php?error=emptyfields");
		exit();
	} else if ($password != $passwordRepeat) {
		header ("Location: ../passwordReset.php?error=nomatch");
		exit();
	} 
	
	// Check the current date to see if token has expired
	$currentDate = date("U");
	
	require 'dbh.inc.php';
	
	// Select token from database
	$sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= ? ";
	$stmt = mysqli_stmt_init($conn); 
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		// If the sql statement fails
		echo "There was a sql error when getting selector and token expiration information.";
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
		mysqli_stmt_execute($stmt);
		
		$result = mysqli_stmt_get_result($stmt);
		if (!$row = mysqli_fetch_assoc($result)) {
			// No results
			echo $selector;
			echo "<br>";
			echo $currentDate;
			echo "<br>";
			echo "No results found for selector and expiration date. Please resubmit your request.";
			exit();
		} else {
	
			// convert validator token into binary
			$tokenBin = hex2bin($validator);
			// Match with token in DB
			$tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);
			if ($tokenCheck === false) {
				echo "Token mismatch. You need to resubmit";
				exit();
			} elseif($tokenCheck === true) {
				// correct user
				// Now update password
				
				$tokenEmail = $row['pwdResetEmail'];
				
				$sql = "SELECT * FROM users WHERE email=?";
				$stmt = mysqli_stmt_init($conn);
					if (!mysqli_stmt_prepare($stmt, $sql)) {
						// If the sql statement fails
						echo "There was a sql error in finding email address.";
						exit();
					} else {

						// get user from users table
						mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
						mysqli_stmt_execute($stmt);
	
					$result = mysqli_stmt_get_result($stmt);
					if (!$row = mysqli_fetch_assoc($result)) {
							// If the sql statement fails
							echo "There was an error in finding token email.";
							exit();
						} else {
							
							$sql = "UPDATE users SET password=? WHERE email=?";
							$stmt = mysqli_stmt_init($conn);
							
							if (!mysqli_stmt_prepare($stmt, $sql)) {
								// If the sql statement fails
								echo "There was an error updating the password. Try again.";
								exit();
							} else {
								// Hash password to be submitted
								$newPwdHash = password_hash($password, PASSWORD_DEFAULT);
								mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
								mysqli_stmt_execute($stmt);
								
								// Delete the token from the db
								$sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
								$stmt = mysqli_stmt_init($conn);
			
								if (!mysqli_stmt_prepare($stmt, $sql)) {
									
									// HITTING THIS ERROR
									
									echo "There was an error removing the reset token.";
									exit();
								} else {
									mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
									mysqli_stmt_execute($stmt);
									header ("Location: ../adminLogin.php?newpwd=passwordupdated");
								}
								
							}
							
							
						}
					}
			}
		}
	}
	
	
} else {
	header("Location: ../index.php");
}