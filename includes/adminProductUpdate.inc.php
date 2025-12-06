<?php



if (isset($_POST['product-update'])) {

	

	// Set up token in database

	require 'dbh.inc.php';

	

	$productID 			= $_POST["id"];

	$product_number 	= $_POST["product_number"];

	$product_name 		= $_POST["product_name"];

	$product_desc 		= $_POST["product_desc"];

	$product_desc_sp 	= $_POST["product_desc_sp"];



	$product_status		= $_POST["product_status"];

	

	$wholesale_price 	= $_POST["wholesale_price"];

	$retail_price 		= $_POST["retail_price"];

	

	$image_path 		= $_POST["image_path"];

	$inventory_count 	= $_POST["inventory_count"];

	

	$product_type 		= $_POST["product_type"];

	$product_material 	= $_POST["product_material"];

	$product_supplier 	= $_POST["product_supplier"];

	$supplier_url 		= $_POST["supplier_url"];

	

	$sql = "SELECT * FROM products WHERE id=?";

	$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {

			// If the sql statement fails

			echo "There was a sql error in finding the product.";

			exit();

		} else {

			// get the product

			mysqli_stmt_bind_param($stmt, "i", $productID);

			mysqli_stmt_execute($stmt);

	

			$result = mysqli_stmt_get_result($stmt);

			if (!$row = mysqli_fetch_assoc($result)) {

				echo "<p>There was an error finding the product.</p>";

			} else {				

				

				// price_wholesale =?, price_retail =?, file_name =?, count =?, type =? supplier =? url =?,

				$sql = "UPDATE products SET prod_num =?, name =?, description =?, desc_sp =?, price_wholesale =?, price_retail =?, file_name =?, count =?, type =?, material =?, supplier =?, url =?, status =? WHERE id=?";

				

				

				$stmt = mysqli_stmt_init($conn);

				if (!mysqli_stmt_prepare($stmt, $sql)) {

					// If the sql statement fails

					echo "There was an error updating the the product. Try again? I guess?";

					exit();

				} else { 

					// statement is fine, now execute the statement

					// twelve items ssssddsiisis

					mysqli_stmt_bind_param($stmt, "ssssddsiisisii", $product_number, $product_name, $product_desc, $product_desc_sp, $wholesale_price, $retail_price, $image_path, $inventory_count, $product_type, $product_material, $product_supplier, $supplier_url,  $product_status, $productID);

					mysqli_stmt_execute($stmt);

					

					// header ("Location: ../adminProducts.php?updateStatus=success&updatedProductName=$product_name&updatedProductType=$product_type");

					header ("Location: ../adminProducts.php?updateStatus=success&updatedProductName=$product_name&pid=$product_type");

					// updatedProductType

					// updatedProductName

				

				}

				

			}

		

		}



} else {

	header("Location: ../index.php");

	exit();

}