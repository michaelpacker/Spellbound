<?php

if (isset($_POST['supplier-update'])) {
	
	// Set up token in database
	require 'dbh.inc.php';
	
	$supplierID		= $_POST["id"];
	$supplier_name 	= $_POST["supplier_name_update"];
	$supplier_url 	= $_POST["supplier_url_update"];
	$supplier_email	= $_POST["supplier_email_update"];

	$show_url 			= $_POST["show_url_update"];
	
	$sql = "SELECT * FROM suppliers WHERE supplierID=?";
	$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			// If the sql statement fails
			echo "There was a sql error in finding the show.";
			exit();
		} else {
			// get the product
			mysqli_stmt_bind_param($stmt, "i", $supplierID);
			mysqli_stmt_execute($stmt);
	
			$result = mysqli_stmt_get_result($stmt);
			if (!$row = mysqli_fetch_assoc($result)) {
				echo "<p>There was a general error finding the supplier.</p>";
			} else {				
				

				$sql = "UPDATE suppliers SET name =?, url =?, email =? WHERE supplierID=?";
				
				
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					// If the sql statement fails
					echo "There was an error updating the the supplier. Try again? I guess?";
					exit();
				} else { 
					// statement is fine, now execute the statement
					mysqli_stmt_bind_param($stmt, "sssi", $supplier_name, $supplier_url, $supplier_email, $supplierID);
					mysqli_stmt_execute($stmt);
					
					header ("Location: ../adminSuppliers.php?updateStatus=success&updatedName=$supplier_name");
				
				}
				
			}
		
		}

} else {
	header("Location: ../index.php");
	exit();
}