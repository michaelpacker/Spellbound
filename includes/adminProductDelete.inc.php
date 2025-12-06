<?php

if (isset($_POST['product-delete'])) { 
	
	// Set up token in database
	require 'dbh.inc.php';

	$productID 		    = $_POST['deleted-product-id'];
	$product_name 		= $_POST['deleted-product-name'];
    $product_type       = $_POST['deleted-product-type'];
	
	

	// Use prepared statements
	$sql = "DELETE FROM products WHERE id=?;";
	$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) { 
			// If the sql statement fails
			echo "The SQL statement failed.";
			exit();
		} else {
			
			// sql statement did not fail
			mysqli_stmt_bind_param($stmt, "i", $productID);
			// run parameters inside database
			mysqli_stmt_execute($stmt);
			
			header("Location: ../adminProducts.php?deleteStatus=success&deletedName=$product_name");
			exit();
	
		}
	

} else {
	header("Location: ../index.php");
	exit();
}