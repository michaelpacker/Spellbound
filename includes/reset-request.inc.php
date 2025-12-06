<?php

if (isset($_POST['reset-request-submit'])) {
	// Start with tokens (two tokens)
	// Must be cryptographically secure
	$selector = bin2hex(random_bytes(8));
	$token = random_bytes(32);
	
	// Create the link we're seinding in the email
	$url = "www.spellboundsparkly.com/passwordReset.php?selector=" . $selector . "&validator=" . bin2hex($token);
	
	// Create expiration for token
	// Add 2 hrs to todays date from 1970
	$expires = date("U") + 3600;
	
	// Set up token in database
	//pwdReset
	
	// Connect to DB
	require 'dbh.inc.php';
	
	$userEmail = $_POST["email"];
	
	// Delete any existing tokens from THIS user in the database
	$sql = "DELETE FROM pwdReset WHERE pwdResetEmail =?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		// If the sql statement fails
		echo "There was an error";
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, "s", $userEmail);
		mysqli_stmt_execute($stmt);
	}
	
	// Now insert token
	$sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?,?,?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		// If the sql statement fails
		echo "There was an error";
		exit();
	} else {
		// Hash sensitive information
		$hashedToken = password_hash($token, PASSWORD_DEFAULT);
		// Do the insert
		mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
		mysqli_stmt_execute($stmt);
	}
	
	mysqli_stmt_close($stmt);
	// Really close the connection
	mysqli_close();
	
	// SEND THE EMAIL
	$to = $userEmail;
	
	$subject = "Spellbound Password Reset Request";
	
	$message = '<p>Hey! We received a password reset request on your behalf. Use the link below to reset that sucker.</p><p>If you did not make this request, you can ignore this email.</p>';
	
	$message .= '<p><a href="' . $url . '">' . $url . '</a></p>';
	$message .= '<p>Please do not reply to this email. We have no idea what might happen if you do.</p>';
	
	$headers = "From: Spellbound Jewelry <jewelryspellbound@gmail.com>\r\n";
	$headers .= "Reply-To: jewelryspellbound@gmail.com\r\n";
	$headers .= "Content-type: text/html\r\n";
	

	
	mail($to, $subject, $message, $headers);
	header("Location: ../passwordResetRequest.php?reset=success");
	
} else {
	header("Location: ../index.php");
}